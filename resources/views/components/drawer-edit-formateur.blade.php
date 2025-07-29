<div class="offcanvas offcanvas-end !w-[45em]" tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
    <div class="flex flex-col w-full">
        <div class="w-full px-4 py-2 inline-flex items-center justify-between bg-gray-100">
            <p class="text-lg text-gray-500 font-medium">Menu</p>
            <a data-bs-toggle="offcanvas" href="#offcanvas" role="button" aria-controls="offcanvas"
                class="w-10 h-10 rounded-md hover:text-inherit hover:bg-gray-200 duration-200 cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-xmark text-gray-500"></i>
            </a>
        </div>
        <div class="w-full overflow-x-auto p-4 overflow-y-auto h-[100vh] pb-3">
            <div class="flex flex-col gap-y-3">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-[#a462a4]">Modifier votre profil</h1>
                </div>
                <form action="{{ route('profile.update.form', Auth::user()->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-col w-full gap-3">
                        <x-input name="name" type="text" label="Nom" value="{{ Auth::user()->name }}" />
                    </div>
                    <div class="flex flex-col w-full gap-3">
                        <x-input name="firstName" type="text" label="Prénom" value="{{ Auth::user()->firstName }}" />
                    </div>
                    <h1 class="font-semibold text-[#a462a4] text-lg mt-4">Information professionnel</h1>
                    <div class="flex flex-col w-full gap-3">
                        <x-input name="specialite" type="specialite" label="Spécialité" value="{{ $form->form_speciality }}" />
                    </div>
                    <div class="flex flex-col w-full gap-3">
                        <x-input name="fonction" type="fonction" label="Fonction actuelle" value="{{ $form->form_titre }}" />
                    </div>
                    <h1 class="font-semibold text-[#a462a4] text-lg mt-4">Contact</h1>
                    <div class="flex flex-col w-full gap-3">
                        <x-input name="email" type="email" label="Email" value="{{ $form->email }}" />
                    </div>
                    <div class="flex flex-col w-full gap-3">
                        <x-input name="phone" type="phone" label="Téléphone" value="{{ $form->phone }}" />
                    </div>
                    <input type="submit"
                        class="focus:outline-none px-2 bg-[#a462a4] py-2 px-5 mt-2 rounded-lg text-white hover:scale-105 hover:bg-[#a462a4]/80 transition duration-200 text-md"
                        value="Sauvegarder">
                </form>
            </div>
        </div>
    </div>
</div>
