<div class="flex flex-col h-[550px] bg-white w-full relative">

    {{-- Contextual Action Bar --}}
    <div class="px-6 py-3 border-b border-slate-100 bg-slate-900 flex justify-between items-center shadow-md z-10">
        <span class="text-xs font-mono font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
            SYSTEM CHANNEL: <span class="text-white">[{{ strtoupper($activeContext) }}]</span>
        </span>

        @if (!$isReportMode)
            <button wire:click="generateDocument" wire:loading.attr="disabled"
                class="px-4 py-2 text-xs font-mono font-bold rounded-xl transition shadow-sm flex items-center gap-2 border bg-slate-800 text-slate-200 border-slate-700 hover:bg-slate-700 hover:text-white">
                RUN SYSTEM DIAGNOSTIC
            </button>
        @else
            <button wire:click="toggleReportMode"
                class="px-4 py-2 text-xs font-mono font-bold rounded-xl transition shadow-sm flex items-center gap-2 border bg-slate-800 text-slate-200 border-slate-700 hover:bg-rose-900 hover:border-rose-700 hover:text-white">
                CLOSE REPORT & RETURN TO CHAT
            </button>
        @endif
    </div>

    @if ($isReportMode)
        {{-- ========================================== --}}
        {{-- NATIVE INTERACTIVE INTELLIGENCE REPORT     --}}
        {{-- ========================================== --}}
        <div class="flex-1 bg-slate-50 overflow-y-auto relative scroll-smooth">
            {{-- Action Tools for the Report --}}
            <div
                class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-200 px-8 py-4 flex justify-between items-center z-10">
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider">Executive Intelligence
                        Briefing</h3>
                    <p class="text-[10px] text-slate-500 font-mono mt-0.5">GENERATED:
                        {{ now()->format('Y-m-d H:i:s T') }} // SEC_LEVEL: ALPHA</p>
                </div>
                <button onclick="window.print()"
                    class="text-xs font-bold bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg hover:bg-indigo-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print to Paper
                </button>
            </div>

            {{-- The Rendered Data --}}
            <div class="p-8 pb-20">
                <div class="bg-white border border-slate-200 shadow-sm rounded-xl p-8 max-w-4xl mx-auto">
                    {{-- Formats the AI text beautifully natively in the browser --}}
                    <div class="font-sans text-sm text-slate-800 leading-relaxed whitespace-pre-wrap">
                        {{ $draftedContent }}
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- ========================================== --}}
        {{-- THE CHAT INTERFACE                         --}}
        {{-- ========================================== --}}

        {{-- The Banner that appears after Generation --}}
        @if ($documentDrafted)
            <div
                class="px-6 py-4 bg-slate-950 border-b border-indigo-950/40 flex justify-between items-center transition-all animate-fadeIn">
                <div>
                    <h4
                        class="text-xs font-mono font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                        ⚡ INTELLIGENCE REPORT RECONSTITUTED
                    </h4>
                </div>

                <button wire:click="toggleReportMode"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-mono font-bold rounded-xl shadow-lg flex items-center gap-2 transition tracking-wider">
                    LAUNCH INTERACTIVE BRIEFING
                </button>
            </div>
        @endif

        {{-- Chat Messages Area --}}
        <div id="dss-chat-container" x-data x-init="$watch('$wire.messages', () => { $nextTick(() => { $el.scrollTop = $el.scrollHeight }) })"
            class="p-6 flex-1 overflow-y-auto flex flex-col gap-5 bg-slate-950/50 custom-scrollbar scroll-smooth">

            @foreach ($messages as $index => $msg)
                <div wire:key="msg-{{ $index }}"
                    class="flex flex-col {{ $msg['role'] === 'user' ? 'items-end' : 'items-start' }}">
                    <span class="text-[9px] font-mono font-bold uppercase tracking-widest text-slate-500 mb-1.5 px-1">
                        {{ $msg['role'] === 'user' ? '// SEC_HEAD_INPUT' : '// DSS_CORE_OUTPUT' }}
                    </span>
                    <div
                        class="px-5 py-3.5 rounded-2xl max-w-[85%] text-sm font-sans shadow-sm whitespace-pre-wrap leading-relaxed border
                        {{ $msg['role'] === 'user' ? 'bg-slate-900 border-slate-800 text-slate-100 rounded-tr-sm' : 'bg-white border-slate-200 text-slate-900 rounded-tl-sm' }}">
                        {{ $msg['text'] }}
                    </div>
                </div>
            @endforeach

            {{-- Loading Indicator --}}
            <div wire:loading wire:target="sendMessage, generateDocument" class="flex flex-col items-start mt-2">
                <span
                    class="text-[9px] font-mono font-bold uppercase tracking-widest text-indigo-500 mb-1.5 px-1 animate-pulse">EXECUTING
                    ANALYTICS ALGORITHM...</span>
                <div
                    class="px-5 py-4 bg-white border border-slate-200 rounded-2xl rounded-tl-sm shadow-sm flex gap-1.5 items-center">
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce" style="animation-delay:0.15s">
                    </div>
                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-bounce" style="animation-delay:0.3s">
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Form --}}
        <form wire:submit.prevent="sendMessage" class="p-4 border-t border-slate-200 bg-white flex gap-3 z-10">
            <input wire:model="input" type="text"
                placeholder="Interrogate data anomalies, target metrics, or request forecasting..."
                class="flex-1 text-sm font-mono border border-slate-200 rounded-xl px-4 py-3 text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition"
                wire:loading.attr="disabled" />
            <button type="submit" wire:loading.attr="disabled"
                class="shrink-0 px-6 bg-slate-900 hover:bg-slate-800 disabled:opacity-50 text-white text-xs font-mono font-bold rounded-xl transition shadow-md tracking-widest">
                QUERY
            </button>
        </form>
    @endif
</div>
