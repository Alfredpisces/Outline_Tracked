<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-800">{{ __('Edit Personnel Account') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-6">
            <form action="{{ route('admin.personnel.update', $personnel->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input name="employee_id" value="{{ old('employee_id', $personnel->employee_id) }}"
                        placeholder="Employee ID" class="rounded-xl border-slate-300 w-full text-sm" required>
                    <input name="email" type="email" value="{{ old('email', $personnel->email) }}"
                        placeholder="Email" class="rounded-xl border-slate-300 w-full text-sm" required>
                    <input name="first_name" value="{{ old('first_name', $personnel->first_name) }}"
                        placeholder="First Name" class="rounded-xl border-slate-300 w-full text-sm" required>
                    <input name="last_name" value="{{ old('last_name', $personnel->last_name) }}"
                        placeholder="Last Name" class="rounded-xl border-slate-300 w-full text-sm" required>
                </div>

                <!-- COT Profiling Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                    <div class="w-full">
                        <label
                            class="block text-[10px] font-bold text-blue-800 uppercase tracking-widest mb-1">Department</label>
                        <select name="department" class="rounded-xl border-slate-300 w-full text-sm text-slate-700">
                            <option value="">Select Department (Optional)</option>
                            @foreach (['Math', 'Science', 'English', 'Filipino', 'MAPEH', 'TLE', 'AP', 'Values'] as $dept)
                                <option value="{{ $dept }}" @selected(old('department', $personnel->department) === $dept)>{{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="block text-[10px] font-bold text-blue-800 uppercase tracking-widest mb-1">Official
                            DepEd Rank</label>
                        <!-- Notice: 'required' attribute removed to support cross-role flexibility -->
                        <select name="deped_rank" class="rounded-xl border-slate-300 w-full text-sm text-slate-700">
                            <option value="" disabled>Select Official DepEd Rank...</option>

                            <optgroup label="Proficient Tier (Standard COT)">
                                @foreach (['Teacher I', 'Teacher II', 'Teacher III', 'Special Science Teacher I'] as $rank)
                                    <option value="{{ $rank }}" @selected(old('deped_rank', $personnel->deped_rank) === $rank)>{{ $rank }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Highly Proficient Tier (Advanced COT)">
                                @foreach (['Master Teacher I', 'Master Teacher II', 'Master Teacher III', 'Master Teacher IV'] as $rank)
                                    <option value="{{ $rank }}" @selected(old('deped_rank', $personnel->deped_rank) === $rank)>
                                        {{ $rank }}</option>
                                @endforeach
                            </optgroup>

                            <optgroup label="School Leadership & Observers">
                                @foreach (['Head Teacher I', 'Head Teacher IV', 'Principal I'] as $rank)
                                    <option value="{{ $rank }}" @selected(old('deped_rank', $personnel->deped_rank) === $rank)>
                                        {{ $rank }}</option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Guidance & Support Services">
                                @foreach (['Guidance Counselor', 'Administrative Officer', 'Administrative Assistant'] as $rank)
                                    <option value="{{ $rank }}" @selected(old('deped_rank', $personnel->deped_rank) === $rank)>
                                        {{ $rank }}</option>
                                @endforeach
                            </optgroup>

                            <optgroup label="System Level">
                                <option value="System Administrator" @selected(old('deped_rank', $personnel->deped_rank) === 'System Administrator')>IT / System
                                    Administrator</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <!-- Password Reset Section -->
                <div class="p-4 bg-indigo-50/50 border border-indigo-100 rounded-xl space-y-3">
                    <p class="text-sm font-bold text-indigo-900">Reset Password</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input name="password" type="password" placeholder="New Password (Leave blank to keep current)"
                            class="rounded-xl border-slate-300 w-full text-sm">
                        <input name="password_confirmation" type="password" placeholder="Confirm New Password"
                            class="rounded-xl border-slate-300 w-full text-sm">
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl">
                    <p class="text-sm font-bold text-slate-700 mb-3">Assign System Roles</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        @foreach ($roles as $role)
                            <label
                                class="inline-flex items-center gap-2 p-2 rounded-lg hover:bg-white border border-transparent cursor-pointer">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    @checked($personnel->roles->contains($role->id)) class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="text-slate-700 font-medium capitalize">
                                    {{ str_replace('_', ' ', $role->name) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.personnel.index') }}"
                        class="text-slate-600 px-4 py-2 text-sm font-bold hover:text-slate-900">Cancel</a>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700">
                        Update Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
