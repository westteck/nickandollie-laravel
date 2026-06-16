<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-night">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-body/80">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion-modal">
        {{ __('Delete Account') }}
    </button>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="confirm-user-deletion-modal" tabindex="-1" aria-labelledby="confirm-user-deletion-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirm-user-deletion-label">{{ __('Are you sure you want to delete your account?') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mb-3">
                            <label for="delete-password" class="form-label sr-only">{{ __('Password') }}</label>
                            <input id="delete-password" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}" autocomplete="current-password">
                            @foreach ($errors->userDeletion->get('password') ?? [] as $message)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('confirm-user-deletion-modal');
            if (modalEl) new bootstrap.Modal(modalEl).show();
        });
    </script>
    @endif
</section>
