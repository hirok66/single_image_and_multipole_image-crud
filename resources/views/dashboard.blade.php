<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Welcome to Your Dashboard</h1>
                    <p>This is your application dashboard. Here you can manage your settings and view your data.</p>
                </div>

                <div class="p-4 text-gray-900">
                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="exampleInput" class="block text-sm font-medium text-gray-700">Image
                                Input</label>
                            <input type="file" name="image" id="exampleInput"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('image')
                                <p class="text-red-500 text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">Select Images</label>
                            <input type="file" name="photos[]" id="images"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" multiple
                                onchange="previewImages(event)">
                            @error('photos')
                                <p class="text-red-500 text-danger text-sm mt-1 bg-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button type="submit"
                                class="btn btn-primary mt-4 px-4 py-2 bg-blue-600 text-primary rounded-md">
                                Submit
                            </button>
                        </div>

                </div>

            </div>
        </div>
        {{-- Image Upload Section --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Image show</h1>
                    <p>This is your application dashboard. Here you can manage your settings and view your data.</p>
                </div>
 <hr> <hr>
                <div class="p-4 text-gray-900 grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">


<h2 class="text-xl font-semibold mb-4">Profile Image</h2>

@if ($user->image)
    <img src="{{ asset($user->image) }}" alt="Profile Image" width="150">
@else
    <p>No profile image uploaded</p>
@endif

<hr class="my-6">

<h2 class="text-xl font-semibold mb-4">All Photos</h2>

@if (!empty($images))
    <table class="table-auto border-collapse border border-gray-300 w-full">
    <tbody>
        <tr>
            @php $count = 0; @endphp

            @foreach ($images as $key => $image)
                @if ($user->image !== $image)

                    <td class="border border-gray-300 p-2 text-center">
                        <img src="{{ asset($image) }}"
                             class="w-32 h-24 object-cover mx-auto mb-2">


                    </td>

                    @php $count++; @endphp
                    @if ($count % 5 == 0)
                        </tr><tr>
                    @endif

                @endif
            @endforeach
        </tr>
    </tbody>
</table>

@else
    <p>No photos uploaded</p>
@endif







                </div>
            </div>
        </div>
</x-app-layout>
