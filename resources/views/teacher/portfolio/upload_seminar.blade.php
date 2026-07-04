<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Add Professional Growth') }}</h2></x-slot>
    <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('teacher.performance.store_seminar') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div><label for="title" class="block text-sm font-medium text-gray-700">Seminar / Training Title</label><input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label for="date_attended" class="block text-sm font-medium text-gray-700">Date Attended</label><input type="date" name="date_attended" id="date_attended" value="{{ old('date_attended') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
                <div><label for="points" class="block text-sm font-medium text-gray-700">Claimed Points / Hours</label><input type="number" step="0.5" min="0.5" name="points" id="points" value="{{ old('points') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
            </div>
            <div><label for="certificate_file" class="block text-sm font-medium text-gray-700">Certificate File</label><input id="certificate_file" name="certificate_file" type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" accept=".pdf,.jpg,.jpeg,.png"></div>
            <div class="flex justify-end"><button type="submit" class="inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Submit Certificate</button></div>
        </form>
    </div>
</x-app-layout>
