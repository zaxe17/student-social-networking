<div id="editprofModal" class="modal hidden">
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black/35 w-full h-screen py-8 flex justify-center items-center z-50 backdrop-blur-[1px]">

        <div class="w-2/5 h-3/4 bg-[#F5F5F5] form-shadow rounded-3xl backdrop-blur-sm overflow-hidden flex flex-col">

            <div class="shadow-postheader w-full pb-3 pt-7 relative">
                <h2 class="text-center text-xl font-medium">{{ $title }}</h2>

                <span close-modal class="icon bg-black absolute top-10 right-0 -translate-x-1/2 -translate-y-1/2 transition-all duration-300 cursor-pointer" style="--svg: url('https://api.iconify.design/material-symbols-light/close-rounded.svg'); --size: 35px;"></span>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col flex-1 overflow-y-auto p-8 gap-9" enctype="multipart/form-data">
                @csrf

                <div class="p-8 pb-2">
                    <!-- USER PORFILE PIC -->
                    <div class="flex flex-col justify-center items-center gap-2">
                        <img src="/img/user.png" alt="" class="w-40 h-40">
                        <label class="block w-50 mx-auto text-center cursor-pointer">
                            <span class="flex justify-center items-center bg-[#770d08] text-white py-2 rounded-lg hover:bg-[#5a0a06] transition">
                                <span close-modal class="icon bg-white cursor-pointer" style="--svg: url('https://api.iconify.design/material-symbols/upload.svg'); --size: 25px;"></span>
                                Upload Photo
                            </span>
                            <input type="file" class="hidden">
                        </label>
                    </div>
                </div>

                <!-- USER INPUT FIELDS -->
                <div class="grid grid-cols-12 gap-1.5">
                    <div class="flex flex-col gap-1.5 col-span-6">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" value="" class="bg-[#000000]/10 py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                    <div class="flex flex-col gap-1.5 col-span-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" value="" class="bg-[#000000]/10 py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                    <div class="flex flex-col gap-1.5 col-span-12">
                        <label for="">Bio</label>
                        <input type="text" name="bio" value="" class="bg-[#000000]/10 py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                </div>

                <!-- USER LINKS -->
                <div class="flex flex-col">
                    <label for="">Acount links</label>

                    <div class="flex items-center gap-2">
                        <span class="icon transition-all duration-300 bg-black" style="--svg: url('https://api.iconify.design/mdi/instagram.svg'); --size: 45px; --icon-color: black;"></span>
                        <input type="text" name="instagram" value="" placeholder="https://www.instagram.com/username/" class="w-full py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="icon transition-all duration-300 bg-[#0e2391]" style="--svg: url('https://api.iconify.design/mdi/facebook.svg'); --size: 45px; --icon-color: black;"></span>
                        <input type="text" name="facebook" value="" placeholder="https://www.facebook.com/example.username/" class="w-full py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="icon transition-all duration-300 bg-[#0a66c2]" style="--svg: url('https://api.iconify.design/mdi/linkedin.svg'); --size: 45px; --icon-color: black;"></span>
                        <input type="text" name="linkedin" value="" placeholder="https://www.linkedin.com/in/username-example-b86a00296/" class="w-full py-1.5 px-2 rounded-lg focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="px-15 py-1 bg-[#770d08] text-white text-lg rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>