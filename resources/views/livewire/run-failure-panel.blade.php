<div
    x-data="runFailurePanel({ runId: '{{ $run->getKey() }}', initialStatus: '{{ strtolower($run->status ?? '') }}' })"
    x-init="init()"
    class="max-w-2xl bg-white border border-gray-200 rounded-lg shadow-sm p-4"
>
    <div class="flex items-start justify-between">
        <div class="flex items-center space-x-3">
            <div class="text-sm font-medium text-gray-900">Run Recovery</div>
            <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Run #{{ $run->getKey() }}</span>
            <template x-if="status">
                <span
                    x-text="statusLabel"
                    class="text-xs font-semibold ml-2 px-2 py-0.5 rounded"
                    :class="status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'"
                ></span>
            </template>
        </div>
        <div class="text-xs text-gray-500">Last updated: <span>{{ optional($run->last_failed_at)->diffForHumans() ?? '—' }}</span></div>
    </div>

    <div class="mt-4">
        <template x-if="loading">
            <div class="flex items-center space-x-2 text-gray-600">
                <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span>Analyzing failure...</span>
            </div>
        </template>

        <template x-if="error">
            <div class="text-sm text-red-700 bg-red-50 border border-red-100 rounded p-3">
                <div class="font-semibold">Could not load analysis</div>
                <div x-text="error" class="mt-1 text-sm"></div>
            </div>
        </template>

        <template x-if="analysis">
            <div class="mt-2 space-y-4">
                <div class="p-3 bg-gray-50 border border-gray-100 rounded">
                    <div class="text-sm font-semibold text-gray-800">Why this run failed</div>
                    <div class="mt-2 text-sm text-gray-700" x-text="analysis.reason"></div>
                    <div class="mt-1 text-xs text-gray-400">Confidence: <span x-text="(analysis.confidence * 100).toFixed(0) + '%'"></span></div>
                </div>

                <div class="p-3 border border-gray-100 rounded">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-gray-800">Suggested fixes & next steps</div>
                        <button
                            class="text-xs text-gray-500 hover:text-gray-700"
                            @click="open = !open"
                            x-text="open ? 'Hide' : 'Show'"
                        ></button>
                    </div>

                    <ul x-show="open" x-collapse class="mt-3 space-y-2 text-sm text-gray-700">
                        <template x-for="(s, i) in analysis.suggestions" :key="i">
                            <li class="flex items-start space-x-2">
                                <svg class="h-4 w-4 text-green-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span x-text="s"></span>
                            </li>
                        </template>
                    </ul>
                </div>

                <div class="flex items-center space-x-3">
                    <button
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700"
                        :disabled="retrying || status !== 'failed'"
                        @click.prevent="retry()"
                    >
                        <template x-if="retrying">
                            <svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        </template>
                        <span x-text="retrying ? 'Scheduling retry...' : 'Retry run' "></span>
                    </button>

                    <button
                        class="inline-flex items-center px-3 py-1.5 bg-white text-gray-700 border border-gray-200 rounded text-sm hover:bg-gray-50"
                        @click="copyRunId()"
                    >
                        Copy run ID
                    </button>

                    <div x-show="message" x-text="message" class="text-sm text-green-600"></div>
                </div>

                <template x-if="postActionError">
                    <div class="text-sm text-red-700 bg-red-50 border border-red-100 rounded p-3" x-text="postActionError"></div>
                </template>
            </div>
        </template>

    </div>

    <script>
        function runFailurePanel({ runId, initialStatus }) {
            return {
                runId,
                status: initialStatus,
                statusLabel: initialStatus === 'failed' ? 'Failed' : initialStatus.charAt(0).toUpperCase() + initialStatus.slice(1),
                loading: false,
                open: true,
                analysis: null,
                error: null,
                retrying: false,
                message: null,
                postActionError: null,

                init() {
                    // Only fetch analysis when the run is failed to avoid noise.
                    if (this.status !== 'failed') {
                        // If not failed, do a lightweight probe to still show neutral info.
                        this.fetchAnalysis();
                        return;
                    }

                    this.fetchAnalysis();
                },

                async fetchAnalysis() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const res = await fetch(`/runs/${this.runId}/failure-analysis`, {
                            headers: {
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });

                        if (!res.ok) {
                            const text = await res.text();
                            throw new Error(text || 'Failed to fetch analysis');
                        }

                        const payload = await res.json();
                        this.analysis = payload.analysis || { reason: 'No analysis available', suggestions: [], confidence: 0 };

                        // keep status in sync if backend reports it
                        if (payload.status) {
                            this.status = (payload.status || '').toLowerCase();
                            this.statusLabel = this.status === 'failed' ? 'Failed' : (this.status ? this.status.charAt(0).toUpperCase() + this.status.slice(1) : '');
                        }

                    } catch (err) {
                        this.error = err.message || 'Unexpected error while loading analysis';
                    } finally {
                        this.loading = false;
                    }
                },

                async retry() {
                    this.retrying = true;
                    this.postActionError = null;
                    this.message = null;

                    // CSRF token from meta tag if present
                    const tokenEl = document.querySelector('meta[name="csrf-token"]');
                    const csrf = tokenEl ? tokenEl.getAttribute('content') : null;

                    try {
                        const res = await fetch(`/runs/${this.runId}/retry`, {
                            method: 'POST',
                            headers: Object.assign({
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }, csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                            body: JSON.stringify({}),
                            credentials: 'same-origin'
                        });

                        if (!res.ok) {
                            const body = await res.json().catch(() => ({}));
                            const message = body.message || await res.text().catch(() => 'Retry failed');
                            throw new Error(message);
                        }

                        const payload = await res.json();

                        this.message = payload.message || 'Retry scheduled.';
                        // reflect optimistic state change
                        this.status = 'pending';
                        this.statusLabel = 'Pending';

                        // clear analysis because we scheduled a retry
                        this.analysis = null;

                        // Optionally refresh analysis after a short delay
                        setTimeout(() => this.fetchAnalysis(), 1200);

                    } catch (err) {
                        this.postActionError = err.message || 'Failed to schedule retry';
                    } finally {
                        this.retrying = false;
                    }
                },

                copyRunId() {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(this.runId).then(() => {
                            this.message = 'Run ID copied to clipboard.';
                            setTimeout(() => this.message = null, 2500);
                        }).catch(() => {
                            this.postActionError = 'Unable to copy run ID to clipboard.';
                        });
                    } else {
                        // fallback
                        const ta = document.createElement('textarea');
                        ta.value = this.runId;
                        document.body.appendChild(ta);
                        ta.select();
                        try {
                            document.execCommand('copy');
                            this.message = 'Run ID copied to clipboard.';
                            setTimeout(() => this.message = null, 2500);
                        } catch (e) {
                            this.postActionError = 'Unable to copy run ID to clipboard.';
                        }
                        document.body.removeChild(ta);
                    }
                }
            };
        }
    </script>
</div>
