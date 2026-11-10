
<div
    x-data="{
        open: false,
        loading: false,
        unreadCount: 0,
        notifications: [],
        async refresh() {
            try {
                const response = await fetch(
                    '{{ route('notifications.recent') }}',
                    {
                        headers: {
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    }
                );

                if (!response.ok) return;

                const data = await response.json();

                this.notifications = data.notifications || [];
                this.unreadCount = data.unread_count || 0;
            } catch (error) {
                console.error('Notification refresh failed', error);
            }
        },
        async markRead(id) {
            try {
                const response = await fetch(
                    '{{ url('/notifications') }}/' +
                        encodeURIComponent(id) + '/read',
                    {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name=csrf-token]'
                            ).content
                        }
                    }
                );

                if (response.ok) await this.refresh();
            } catch (error) {
                console.error('Mark as read failed', error);
            }
        },
        async markAllRead() {
            try {
                const response = await fetch(
                    '{{ route('notifications.read-all') }}',
                    {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name=csrf-token]'
                            ).content
                        }
                    }
                );

                if (response.ok) await this.refresh();
            } catch (error) {
                console.error('Mark all as read failed', error);
            }
        }
    }"
    x-init="refresh()"
    @focusin.window="if (document.visibilityState === 'visible') refresh()"
    x-on:visibilitychange.document="
        if (document.visibilityState === 'visible') refresh()
    "
    class="relative"
>
    <div
        x-init="
            const interval = setInterval(() => {
                if (document.visibilityState === 'visible') refresh();
            }, 30000);
        "
    ></div>

    <button
        type="button"
        @click="open = !open; if (open) refresh()"
        :aria-expanded="open.toString()"
        aria-label="Notifications"
        class="relative flex h-12 w-12 items-center justify-center rounded-[15px] border border-[#DCE7F7] bg-white text-[#516581] shadow-[0_3px_12px_rgba(30,64,120,0.10)] transition hover:border-[#3478FF] hover:text-[#3478FF]"
    >
        <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"
            />
        </svg>

        <span
            x-cloak
            x-show="unreadCount > 0"
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#E24E61] px-1 text-[9px] font-bold text-white"
        ></span>
    </button>

    <div
        x-cloak
        x-show="open"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-transition.opacity.duration.150ms
        class="absolute right-0 top-[58px] z-[100] w-[min(360px,calc(100vw-32px))] overflow-hidden rounded-[18px] border border-[#DFE8F6] bg-white shadow-[0_15px_45px_rgba(20,45,90,0.18)]"
    >
        <div class="flex items-center justify-between border-b border-[#EDF1F8] px-5 py-4">
            <div>
                <h3 class="text-[14px] font-bold text-[#102243]">
                    Notifications
                </h3>
                <p class="mt-1 text-[10px] text-[#7184A5]">
                    Your latest updates
                </p>
            </div>

            <button
                type="button"
                x-show="unreadCount > 0"
                @click="markAllRead()"
                class="text-[10px] font-bold text-[#3478FF] hover:underline"
            >
                Mark all read
            </button>
        </div>

        <div class="max-h-[350px] overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-5 py-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#EAF2FF] text-[#3478FF]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/>
                        </svg>
                    </div>

                    <p class="mt-4 text-[12px] font-bold text-[#102243]">
                        No notifications yet
                    </p>
                    <p class="mt-2 text-[10px] text-[#8A9AB3]">
                        Your updates will appear here.
                    </p>
                </div>
            </template>

            <template x-for="item in notifications" :key="item.id">
                <div
                    class="border-b border-[#EDF1F8] px-5 py-4 transition hover:bg-[#F8FBFF]"
                    :class="!item.read ? 'bg-[#F5F9FF]' : 'bg-white'"
                >
                    <div class="flex items-start gap-3">
                        <span
                            class="mt-1.5 h-2 w-2 shrink-0 rounded-full"
                            :class="!item.read ? 'bg-[#3478FF]' : 'bg-transparent'"
                        ></span>

                        <div class="min-w-0 flex-1">
                            <p
                                class="break-words text-[12px] font-bold text-[#102243]"
                                x-text="item.title"
                            ></p>

                            <p
                                class="mt-1 break-words text-[11px] leading-[1.7] text-[#7184A5]"
                                x-text="item.message"
                            ></p>

                            <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                                <span
                                    class="text-[10px] text-[#9AA9BD]"
                                    x-text="item.created_at"
                                ></span>

                                <button
                                    x-show="!item.read"
                                    type="button"
                                    @click="markRead(item.id)"
                                    class="text-[10px] font-bold text-[#3478FF] hover:underline"
                                >
                                    Mark read
                                </button>
                            </div>

                            <a
                                x-show="item.url"
                                :href="item.url"
                                class="mt-2 inline-block text-[10px] font-bold text-[#3478FF] hover:underline"
                            >
                                View details
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <a
            href="{{ route('notifications.index') }}"
            class="block border-t border-[#EDF1F8] px-5 py-4 text-center text-[11px] font-bold text-[#3478FF] transition hover:bg-[#F5F9FF]"
        >
            View All Notifications
        </a>
    </div>
</div>
