<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black text-slate-800">{{ __('Manage Personnel') }}</h2>
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl p-6">
            <form action="{{ route('admin.personnel.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <input name="employee_id" placeholder="Employee ID" class="rounded-xl border-slate-300 text-sm"
                        required>
                    <input name="first_name" placeholder="First Name" class="rounded-xl border-slate-300 text-sm"
                        required>
                    <input name="last_name" placeholder="Last Name" class="rounded-xl border-slate-300 text-sm"
                        required>
                    <input name="email" type="email" placeholder="Email" class="rounded-xl border-slate-300 text-sm"
                        required>
                    <input name="password" type="password" placeholder="Password"
                        class="rounded-xl border-slate-300 text-sm" required>
                </div>

                <!-- COT & Profiling Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <select name="department" class="rounded-xl border-slate-300 text-sm text-slate-600">
                        <option value="">Select Department (Optional)</option>
                        <option value="Math">Mathematics</option>
                        <option value="Science">Science</option>
                        <option value="English">English</option>
                        <option value="Filipino">Filipino</option>
                        <option value="MAPEH">MAPEH</option>
                        <option value="TLE">TLE</option>
                        <option value="AP">Araling Panlipunan</option>
                        <option value="Values">Values Education</option>
                    </select>

                    <!-- Notice: 'required' attribute removed to support cross-role flexibility -->
                    <select name="deped_rank" class="rounded-xl border-slate-300 text-sm text-slate-600">
                        <option value="" disabled selected>Select Official DepEd Rank...</option>

                        <optgroup label="Proficient Tier (Standard COT)">
                            <option value="Teacher I">Teacher I</option>
                            <option value="Teacher II">Teacher II</option>
                            <option value="Teacher III">Teacher III</option>
                            <option value="Special Science Teacher I">Special Science Teacher I</option>
                        </optgroup>

                        <optgroup label="Highly Proficient Tier (Advanced COT)">
                            <option value="Master Teacher I">Master Teacher I</option>
                            <option value="Master Teacher II">Master Teacher II</option>
                            <option value="Master Teacher III">Master Teacher III</option>
                            <option value="Master Teacher IV">Master Teacher IV</option>
                        </optgroup>

                        <optgroup label="School Leadership & Observers">
                            <option value="Head Teacher I">Head Teacher I-III</option>
                            <option value="Head Teacher IV">Head Teacher IV-VI</option>
                            <option value="Principal I">Principal I-IV</option>
                        </optgroup>

                        <optgroup label="Guidance & Support Services">
                            <option value="Guidance Counselor">Guidance Counselor (I-III)</option>
                            <option value="Administrative Officer">Administrative Officer (AO II-IV)</option>
                            <option value="Administrative Assistant">Administrative Assistant (ADAS)</option>
                        </optgroup>

                        <optgroup label="System Level">
                            <option value="System Administrator">IT / System Administrator</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Roles Mapping -->
                <div class="p-4 bg-slate-50 border rounded-xl">
                    <p class="text-sm font-bold text-slate-700 mb-3">Assign System Roles</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach ($roles as $role)
                            <label
                                class="inline-flex items-center gap-2 p-2 rounded-lg hover:bg-white border cursor-pointer">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    class="rounded text-indigo-600">
                                <span
                                    class="text-slate-700 font-medium capitalize">{{ str_replace('_', ' ', $role->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                    class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-indigo-700">
                    Create Account & Assign Profile
                </button>
            </form>
        </div>

        <!-- Table Display -->
        <div class="bg-white shadow-sm border rounded-2xl overflow-hidden">
            <table class="min-w-full divide-y">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Personnel</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">DepEd Rank</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">System Roles</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($users as $person)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $person->first_name }} {{ $person->last_name }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $person->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-indigo-700 text-sm">{{ $person->deped_rank ?? 'Unassigned' }}
                                </div>
                                <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">
                                    {{ $person->department }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach ($person->roles as $role)
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-indigo-50 text-indigo-600">
                                        {{ str_replace('_', ' ', $role->name) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.personnel.edit', $person->id) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-bold">Edit</a>

                                    <form action="{{ route('admin.personnel.toggleStatus', $person->id) }}"
                                        method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="{{ $person->is_active ? 'text-amber-600' : 'text-emerald-600' }} hover:underline text-sm font-bold">
                                            {{ $person->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.personnel.destroy', $person->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this account?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-rose-600 hover:text-rose-800 text-sm font-bold">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
