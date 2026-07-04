<div wire:key="copilot-container" class="flex flex-col h-full w-full">

    {{-- Messages Area --}}
    <div id="chat-messages-container"
        class="flex-1 bg-slate-50 p-4 overflow-y-auto flex flex-col gap-3 scroll-smooth h-full">
        @forelse ($messages as $index => $msg)
            <div wire:key="msg-{{ $index }}"
                class="flex flex-col {{ $msg['role'] === 'user' ? 'items-end' : 'items-start' }}">
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">
                    {{ $msg['role'] === 'user' ? 'You' : 'Guide' }}
                </span>
                <div
                    class="px-4 py-2.5 rounded-2xl max-w-[88%] text-sm shadow-sm whitespace-pre-wrap leading-relaxed {{ $msg['role'] === 'user' ? 'bg-indigo-600 text-white rounded-tr-sm' : 'bg-white border border-slate-200 text-slate-700 rounded-tl-sm' }}">
                    {{ $msg['text'] }}
                </div>
            </div>
        @empty
            <div class="flex flex-col items-start mt-2">
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Guide</span>
                <div
                    class="px-4 py-2.5 rounded-2xl max-w-[88%] text-sm shadow-sm whitespace-pre-wrap leading-relaxed bg-white border border-slate-200 text-slate-700 rounded-tl-sm flex items-center gap-2">
                    <span>👋</span> Hello! I'm your System Guide. <br> How can I help you today?
                </div>
            </div>
        @endforelse

        {{-- Typing Indicator --}}
        <div wire:loading wire:target="sendMessage, sendTopic" class="flex flex-col items-start">
            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Guide</span>
            <div
                class="px-4 py-3 bg-white border border-slate-200 rounded-2xl rounded-tl-sm shadow-sm flex gap-1 items-center">
                <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0.15s"></div>
                <div class="w-2 h-2 bg-indigo-400 rounded-full animate-bounce" style="animation-delay:0.3s"></div>
            </div>
        </div>
    </div>

    {{-- Bottom Panel --}}
    <div
        class="bg-white border-t border-slate-100 flex flex-col gap-3 shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] pb-16">

        {{-- DYNAMIC QUICK TOPICS SECTION --}}
        @if (!empty($quickTopics))
            <div class="px-4 pt-3 flex flex-col gap-1.5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Quick topics for
                    your role</p>
                <div class="flex flex-col gap-1.5 overflow-y-auto max-h-36 pr-1 custom-scrollbar">

                    @foreach ($quickTopics as $topic)
                        <button type="button" wire:click.prevent="sendTopic('{{ addslashes($topic['label']) }}')"
                            class="w-full px-3 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-300 text-slate-700 hover:text-indigo-800 rounded-xl text-xs font-bold text-left transition shadow-sm"
                            wire:loading.attr="disabled">
                            {{ $topic['icon'] }} {{ $topic['label'] }}
                        </button>
                    @endforeach

                </div>
            </div>
        @endif

        <form wire:submit.prevent="sendMessage" class="px-4 pb-4 flex gap-2 mt-1">
            <input wire:model="input" type="text" placeholder="Or type your question…"
                class="flex-1 text-xs border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-300 transition"
                wire:loading.attr="disabled" />
            <button type="submit" wire:loading.attr="disabled"
                class="shrink-0 w-10 h-10 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-xl flex items-center justify-center transition shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('chat-updated', () => {
                setTimeout(() => {
                    const container = document.getElementById('chat-messages-container');
                    if (container) container.scrollTop = container.scrollHeight;
                }, 50);
            });
        });
    </script>
</div>
