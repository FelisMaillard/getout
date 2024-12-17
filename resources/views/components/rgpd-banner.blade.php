@once
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!localStorage.getItem('rgpd_accepted')) {
                const banner = document.createElement('div');
                banner.className = 'fixed bottom-0 inset-x-0 pb-2 sm:pb-5 z-50';
                banner.innerHTML = `
                    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                        <div class="rounded-lg bg-purple-900 p-2 shadow-lg sm:p-3">
                            <div class="flex flex-wrap items-center justify-between">
                                <div class="flex w-0 flex-1 items-center">
                                    <p class="ml-3 truncate font-medium text-white">
                                        <span class="inline">
                                            Nous utilisons des cookies pour améliorer votre expérience. En continuant à utiliser ce site, vous acceptez notre politique de confidentialité.
                                        </span>
                                    </p>
                                </div>
                                <div class="mt-0 flex-shrink-0 sm:mt-0 sm:ml-4">
                                    <button type="button" onclick="acceptRGPD()" class="rounded-md bg-white px-4 py-2 font-medium text-purple-900 hover:bg-purple-50">
                                        Accepter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(banner);
            }
        });

        function acceptRGPD() {
            localStorage.setItem('rgpd_accepted', 'true');
            document.querySelector('.fixed.bottom-0.inset-x-0').remove();
        }
    </script>
    @endpush
@endonce
