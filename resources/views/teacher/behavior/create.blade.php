<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Report a Behavioral Incident') }}</h2></x-slot>
    <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg p-6">
        <form action="{{ route('teacher.incidents.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label for="student_lrn" class="block text-sm font-medium text-gray-700">Student LRN</label><input type="text" name="student_lrn" id="student_lrn" value="{{ old('student_lrn') }}" maxlength="12" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
                <div><label for="incident_date" class="block text-sm font-medium text-gray-700">Date of Incident</label><input type="date" name="incident_date" id="incident_date" value="{{ old('incident_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label for="student_first_name" class="block text-sm font-medium text-gray-700">Student First Name</label><input type="text" name="student_first_name" id="student_first_name" value="{{ old('student_first_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                <div><label for="student_last_name" class="block text-sm font-medium text-gray-700">Student Last Name</label><input type="text" name="student_last_name" id="student_last_name" value="{{ old('student_last_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label for="grade_level" class="block text-sm font-medium text-gray-700">Grade Level</label><input type="text" name="grade_level" id="grade_level" value="{{ old('grade_level') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                <div><label for="section" class="block text-sm font-medium text-gray-700">Section</label><input type="text" name="section" id="section" value="{{ old('section') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label for="severity" class="block text-sm font-medium text-gray-700">Severity</label><select name="severity" id="severity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required><option value="minor">Minor</option><option value="major">Major</option></select></div>
                <div><label for="offense_type" class="block text-sm font-medium text-gray-700">Specific Violation</label><input type="text" name="offense_type" id="offense_type" value="{{ old('offense_type') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></div>
            </div>
            <div><label for="narrative" class="block text-sm font-medium text-gray-700">Narrative Description</label><textarea name="narrative" id="narrative" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('narrative') }}</textarea></div>
            <div class="flex justify-end"><button type="submit" class="inline-flex rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white">Submit Incident Report</button></div>
        </form>
    </div>
</x-app-layout>
