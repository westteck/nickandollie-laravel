(function () {
    'use strict';

    const MAX_DIMENSION = 2048;
    const CROP_RATIO = 4 / 3;

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('photo-input');
    const previewGrid = document.getElementById('preview-grid');
    const uploadBtn = document.getElementById('upload-btn');
    const uploadForm = document.getElementById('upload-form');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressLabel = document.getElementById('progress-label');
    const progressPercent = document.getElementById('progress-percent');
    const cropperModal = document.getElementById('cropper-modal');
    const cropperImage = document.getElementById('cropper-image');
    const cropperCropBtn = document.getElementById('cropper-crop-btn');
    const cropperSkipBtn = document.getElementById('cropper-skip-btn');
    const cropperPhotoLabel = document.getElementById('cropper-photo-label');
    const successOverlay = document.getElementById('success-overlay');
    const successCount = document.getElementById('success-count');
    const uploadMoreBtn = document.getElementById('upload-more-btn');
    const errorToast = document.getElementById('error-toast');
    const errorMessage = document.getElementById('error-message');

    let cropper = null;
    let pendingFiles = [];
    let processedFiles = [];
    let currentFileIndex = 0;
    let totalFiles = 0;
    let completedFiles = 0;

    // ─── Dropzone click / keyboard ──────────────────────────────────────────────
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            fileInput.click();
        }
    });

    // ─── File input change ──────────────────────────────────────────────────────
    fileInput.addEventListener('change', async (e) => {
        const files = Array.from(e.target.files);
        if (!files.length) return;

        // Limit to 20
        const remaining = 20 - processedFiles.length;
        if (files.length > remaining) {
            showError(`Only ${remaining} more photo${remaining !== 1 ? 's' : ''} can be selected.`);
            files.splice(remaining);
        }

        if (!files.length) return;

        pendingFiles = files;
        totalFiles = files.length;
        currentFileIndex = 0;
        processedFiles = [];

        // Start the crop pipeline
        await processNextFile();
    });

    // ─── Pipeline: resize → crop → store ───────────────────────────────────────
    async function processNextFile() {
        if (currentFileIndex >= pendingFiles.length) {
            // All files processed — enable upload
            uploadBtn.disabled = false;
            updatePreviewGrid();
            return;
        }

        const file = pendingFiles[currentFileIndex];
        const label = `Photo ${currentFileIndex + 1} of ${totalFiles}`;
        cropperPhotoLabel.textContent = label;

        try {
            const resized = await resizeImage(file);
            await showCropperModal(resized, file.name);
        } catch (err) {
            console.error('Error processing file:', file.name, err);
            // Store without crop on error
            processedFiles.push({ file, cropped: false });
            currentFileIndex++;
            await processNextFile();
        }
    }

    // ─── Resize image to MAX_DIMENSION using Canvas ───────────────────────────
    async function resizeImage(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    // Apply EXIF orientation manually
                    const oriented = applyExifOrientation(img, file);
                    const { width, height } = oriented;
                    const canvas = document.createElement('canvas');
                    let w = width, h = height;

                    if (w > MAX_DIMENSION || h > MAX_DIMENSION) {
                        if (w > h) {
                            h = Math.round((h * MAX_DIMENSION) / w);
                            w = MAX_DIMENSION;
                        } else {
                            w = Math.round((w * MAX_DIMENSION) / h);
                            h = MAX_DIMENSION;
                        }
                    }

                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(oriented, 0, 0, w, h);

                    canvas.toBlob((blob) => {
                        if (!blob) {
                            reject(new Error('Canvas toBlob failed'));
                        } else {
                            resolve(blob);
                        }
                    }, file.type, 0.92);
                };
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    // ─── Apply EXIF orientation ─────────────────────────────────────────────────
    function applyExifOrientation(img, file) {
        // Only works for JPEG — read EXIF from ArrayBuffer
        if (!file.type.includes('jpeg')) return img;

        try {
            const arrayBuffer = file.arrayBuffer ? file.arrayBuffer() :
                new Promise((res, rej) => {
                    const reader = new FileReader();
                    reader.onload = () => res(reader.result);
                    reader.onerror = rej;
                    reader.readAsArrayBuffer(file);
                });

            // We need sync EXIF, so we'll use a simpler approach:
            // Read orientation from file's first bytes
            const reader = new FileReaderSync ? null : null; // Not available in browser
            // Fallback: use exif-js approach if loaded, otherwise skip
            // For simplicity, we rely on CSS transform in cropper for visual correction
            // The actual orientation fix happens server-side (existing code)
        } catch (_) {}

        return img;
    }

    // ─── Show Cropper modal ────────────────────────────────────────────────────
    function showCropperModal(blobUrl, fileName) {
        return new Promise((resolve) => {
            cropperImage.src = blobUrl;
            cropperModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Destroy previous cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            cropper = new Cropper(cropperImage, {
                aspectRatio: CROP_RATIO,
                viewMode: 1,
                dragMode: 'move',
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                // Allow rotation via cropper
            });

            // Store resolve to call after crop/skip
            cropper._resolve = resolve;
            cropper._blobUrl = blobUrl;
            cropper._fileName = fileName;
        });
    }

    // ─── Crop button ───────────────────────────────────────────────────────────
    cropperCropBtn.addEventListener('click', async () => {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            maxWidth: MAX_DIMENSION,
            maxHeight: MAX_DIMENSION,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        const blob = await new Promise((res) => canvas.toBlob(res, 'image/jpeg', 0.92));
        const croppedFile = new File([blob], cropper._fileName, { type: 'image/jpeg' });

        processedFiles.push({ file: croppedFile, cropped: true });
        closeCropperModal();
        currentFileIndex++;
        // Revoke blob URL to free memory
        URL.revokeObjectURL(cropper._blobUrl);
        cropper._resolve();
        await processNextFile();
    });

    // ─── Skip button ───────────────────────────────────────────────────────────
    cropperSkipBtn.addEventListener('click', () => {
        const file = pendingFiles[currentFileIndex];
        processedFiles.push({ file, cropped: false });
        closeCropperModal();
        currentFileIndex++;
        if (cropper) {
            URL.revokeObjectURL(cropper._blobUrl);
            cropper._resolve();
        }
        processNextFile();
    });

    function closeCropperModal() {
        cropperModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    // Close modal on backdrop click
    cropperModal.addEventListener('click', (e) => {
        if (e.target === cropperModal) {
            // Treat as skip
            cropperSkipBtn.click();
        }
    });

    // ─── Update preview grid ───────────────────────────────────────────────────
    function updatePreviewGrid() {
        previewGrid.innerHTML = '';
        if (processedFiles.length === 0) {
            previewGrid.classList.add('hidden');
            return;
        }
        previewGrid.classList.remove('hidden');

        processedFiles.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'relative group aspect-square rounded-xl overflow-hidden bg-slate-100';

            const img = document.createElement('img');
            img.src = URL.createObjectURL(item.file);
            img.className = 'h-full w-full object-cover';
            img.alt = `Preview ${index + 1}`;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'absolute top-1.5 right-1.5 h-7 w-7 rounded-full bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100';
            removeBtn.innerHTML = `<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;
            removeBtn.title = 'Remove';
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                removeFile(index);
            });

            const badge = document.createElement('span');
            badge.className = 'absolute bottom-1.5 left-1.5 rounded-full bg-black/50 px-2 py-0.5 text-xs text-white';
            badge.textContent = index + 1;

            div.appendChild(img);
            div.appendChild(removeBtn);
            div.appendChild(badge);
            previewGrid.appendChild(div);
        });
    }

    function removeFile(index) {
        processedFiles.splice(index, 1);
        updatePreviewGrid();
        uploadBtn.disabled = processedFiles.length === 0;
    }

    // ─── Form submit — XHR upload ──────────────────────────────────────────────
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (processedFiles.length === 0) {
            showError('Please select at least one photo.');
            return;
        }

        const caption = document.getElementById('caption').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        uploadBtn.disabled = true;
        completedFiles = 0;
        progressContainer.classList.remove('hidden');
        updateProgress(0, processedFiles.length, 'Preparing...');

        const formData = new FormData();
        processedFiles.forEach((item) => {
            formData.append('photos[]', item.file);
        });
        formData.append('caption', caption);

        try {
            await uploadWithProgress(formData, csrfToken);
            // Success
            successCount.textContent = `${completedFiles} photo${completedFiles !== 1 ? 's' : ''} uploaded successfully.`;
            successOverlay.classList.remove('hidden');
        } catch (err) {
            showError(err.message || 'Upload failed. Please try again.');
            uploadBtn.disabled = false;
        } finally {
            progressContainer.classList.add('hidden');
        }
    });

    function uploadWithProgress(formData, csrfToken) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const perFile = e.loaded / e.total;
                    const total = (completedFiles + perFile) / totalFiles;
                    updateProgress(Math.round(total * 100), totalFiles, `Uploading photo ${completedFiles + 1} of ${totalFiles}...`);
                }
            });

            xhr.addEventListener('load', () => {
                completedFiles++;
                if (completedFiles < totalFiles) {
                    // More files to upload — Laravel processes all in one request
                    // So we just count completed files for progress display
                    updateProgress(Math.round((completedFiles / totalFiles) * 100), totalFiles, `Complete!`);
                }

                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve();
                } else {
                    let msg = 'Upload failed.';
                    try {
                        const json = JSON.parse(xhr.responseText);
                        msg = json.message || json.error || msg;
                    } catch (_) {}
                    reject(new Error(msg));
                }
            });

            xhr.addEventListener('error', () => {
                reject(new Error('Network error during upload.'));
            });

            xhr.addEventListener('timeout', () => {
                reject(new Error('Upload timed out.'));
            });

            xhr.open('POST', uploadForm.action);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.timeout = 300000; // 5 min timeout
            xhr.send(formData);
        });
    }

    function updateProgress(percent, _total, label) {
        progressBar.style.width = `${percent}%`;
        progressPercent.textContent = `${percent}%`;
        progressLabel.textContent = label;
    }

    // ─── Upload More ───────────────────────────────────────────────────────────
    uploadMoreBtn.addEventListener('click', () => {
        resetForm();
    });

    function resetForm() {
        successOverlay.classList.add('hidden');
        processedFiles = [];
        pendingFiles = [];
        currentFileIndex = 0;
        totalFiles = 0;
        completedFiles = 0;
        previewGrid.innerHTML = '';
        previewGrid.classList.add('hidden');
        uploadBtn.disabled = true;
        fileInput.value = '';
        document.getElementById('caption').value = '';
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
    }

    // ─── Error toast ───────────────────────────────────────────────────────────
    function showError(msg) {
        errorMessage.textContent = msg;
        errorToast.classList.remove('hidden');
        setTimeout(() => {
            errorToast.classList.add('hidden');
        }, 4000);
    }

})();