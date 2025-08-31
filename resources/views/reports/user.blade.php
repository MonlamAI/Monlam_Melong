<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ལས་ཀྱི་སྙོམས་འགྲུབ།') }} (My Activity & Productivity)
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="GET" class="mb-4 flex gap-3 items-end">
                        <div>
                            <label class="block text-sm mb-1">Period</label>
                            <select name="period" class="border rounded px-2 py-1 text-black">
                                @foreach (['hourly','daily','weekly','monthly','yearly'] as $p)
                                    <option value="{{ $p }}" @selected($period===$p)>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Start</label>
                            <input type="date" name="start" value="{{ optional($start)->toDateString() }}" class="border rounded px-2 py-1 text-black" />
                        </div>
                        <div>
                            <label class="block text-sm mb-1">End</label>
                            <input type="date" name="end" value="{{ optional($end)->toDateString() }}" class="border rounded px-2 py-1 text-black" />
                        </div>
                        <button class="bg-indigo-600 text-white px-3 py-2 rounded">Apply</button>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold mb-2">Active Time</h3>
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr>
                                        <th class="border-b py-1">Bucket</th>
                                        <th class="border-b py-1">Heartbeats</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($active as $row)
                                        <tr>
                                            <td class="border-b py-1">{{ $row->bucket }}</td>
                                            <td class="border-b py-1">{{ $row->heartbeats }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="py-2">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Word Contributions</h3>
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr>
                                        <th class="border-b py-1">Bucket</th>
                                        <th class="border-b py-1">Words Created</th>
                                        <th class="border-b py-1">Words Edited</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($words as $row)
                                        <tr>
                                            <td class="border-b py-1">{{ $row->bucket }}</td>
                                            <td class="border-b py-1">{{ (int)($row->words_created ?? 0) }}</td>
                                            <td class="border-b py-1">{{ (int)($row->words_edited ?? 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-2">No data</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
