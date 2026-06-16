(function() {
    var grid = document.getElementById("gallery-grid");
    if (!grid) return; // Not on gallery page

    var urlParams = new URLSearchParams(window.search);
    var contestId = urlParams.get("contest_id") || "";

    // If coming from a contest page, show a banner
    if (contestId) {
        fetch("api/contest.php?id=" + contestId)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.contest) {
                    var banner = document.createElement("div");
                    banner.className = "alert alert-info alert-dismissible fade show mb-3";
                    banner.innerHTML =
                        '<div class="d-flex align-items-center justify-content-between">' +
                        '<div><i class="fas fa-trophy me-2"></i><strong>' + data.contest.title + '</strong> — Click any photo below to enter it.</div>' +
                        '<a href="contest.php?id=' + contestId + '" class="btn btn-sm btn-outline-primary">Back to Contest</a>' +
                        '</div>';
                    var container = document.querySelector(".container-fluid, .container");
                    if (container) container.insertBefore(banner, grid);
                }
            })
            .catch(function() {});
    }

    fetch("api/gallery.php")
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.success || !data.photos.length) {
                grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><i class="fas fa-images fa-3x mb-3"></i><h2 class="h5">No photos yet</h2><p>Be the first to upload!</p></div>';
                return;
            }
            data.photos.forEach(function(p) {
                var col = document.createElement("div");
                col.className = "col-4 col-md-3";
                var altText = p.caption ? p.caption + " by " + p.uploader : "Photo uploaded by " + p.uploader + " on " + new Date(p.uploaded_at).toLocaleDateString("en-US", {year:"numeric", month:"long", day:"numeric"});
                var href = "photo.php?id=" + p.id;
                if (contestId) href += "&contest_id=" + contestId;
                var btn = '';
                if (contestId) {
                    btn = '<a href="' + href + '" class="btn btn-primary btn-sm w-100 mt-1"><i class="fas fa-plus me-1"></i>Enter Contest</a>';
                }
                col.innerHTML = '<a href="' + href + '" class="d-block"><div class="thumbnail" style="aspect-ratio:1/1;"><img src="' + p.thumb_url + '" alt="' + altText + '" loading="lazy"></div></a>' + btn;
                grid.appendChild(col);
            });
        })
        .catch(function() {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><p>Failed to load gallery.</p></div>';
        });
})();
