<template>
    <div class="vocabulary-batch-selector">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <label class="form-label mb-0 fw-semibold">
                        <i class="fas fa-list-ol me-2"></i>
                        Select Range:
                    </label>
                    <select 
                        v-model="selectedBatch" 
                        @change="handleBatchChange"
                        class="form-select form-select-lg"
                        :disabled="loading || !batches.length"
                        style="min-width: 200px;"
                    >
                        <option value="">Choose vocabulary range...</option>
                        <option 
                            v-for="batch in batches" 
                            :key="batch.batch_number"
                            :value="batch.batch_number"
                        >
                            {{ batch.label }} ({{ batch.count }} words)
                        </option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 justify-content-md-end">
                    <!-- Batch Navigation -->
                    <div class="btn-group" v-if="currentBatchInfo">
                        <button 
                            @click="goToPreviousBatch"
                            :disabled="currentBatchInfo.current_batch <= 1 || loading"
                            class="btn btn-outline-primary"
                            title="Previous batch"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button 
                            @click="goToNextBatch"
                            :disabled="currentBatchInfo.current_batch >= currentBatchInfo.total_batches || loading"
                            class="btn btn-outline-primary"
                            title="Next batch"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- Refresh Button -->
                    <button 
                        @click="refreshBatches"
                        :disabled="loading"
                        class="btn btn-outline-secondary"
                        title="Refresh batches"
                    >
                        <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Batch Information Display -->
        <div v-if="currentBatchInfo" class="mt-3">
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <span>
                        Showing <strong>{{ currentBatchInfo.start_index }}-{{ currentBatchInfo.end_index }}</strong> 
                        of <strong>{{ currentBatchInfo.total_count }}</strong> vocabulary words
                        (Batch {{ currentBatchInfo.current_batch }} of {{ currentBatchInfo.total_batches }})
                    </span>
                </div>
                <div class="progress" style="width: 200px; height: 8px;">
                    <div 
                        class="progress-bar bg-info" 
                        :style="{ width: progressPercentage + '%' }"
                    ></div>
                </div>
            </div>
        </div>
        
        <!-- Loading State -->
        <div v-if="loading" class="text-center mt-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading vocabulary batches...</span>
            </div>
            <p class="mt-2 text-muted">Loading vocabulary batches...</p>
        </div>
        
        <!-- Error State -->
        <div v-if="error" class="alert alert-danger mt-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ error }}
            <button @click="refreshBatches" class="btn btn-sm btn-outline-danger ms-2">
                Try Again
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'VocabularyBatchSelector',
    props: {
        batchSize: {
            type: Number,
            default: 100
        },
        autoLoad: {
            type: Boolean,
            default: true
        },
        sortOrder: {
            type: String,
            default: 'asc'
        },
        alphabetFilter: {
            type: String,
            default: ''
        }
    },
    emits: ['batch-selected', 'batch-loaded', 'batch-error'],
    data() {
        return {
            selectedBatch: '',
            batches: [],
            currentBatchInfo: null,
            loading: false,
            error: null,
            cache: new Map(), // Cache for batch data
            lastRequestTime: 0, // For rate limiting
            minRequestInterval: 500, // Minimum 500ms between requests
            sortingChangeTimer: null // Timer for debouncing sorting changes
        };
    },
    computed: {
        progressPercentage() {
            if (!this.currentBatchInfo) return 0;
            return Math.round((this.currentBatchInfo.current_batch / this.currentBatchInfo.total_batches) * 100);
        }
    },
    watch: {
        sortOrder: {
            handler() {
                this.debouncedHandleSortingChange();
            },
            immediate: false
        },
        alphabetFilter: {
            handler() {
                this.debouncedHandleSortingChange();
            },
            immediate: false
        }
    },
    async created() {
        console.log('VocabularyBatchSelector created with props:', {
            batchSize: this.batchSize,
            autoLoad: this.autoLoad,
            sortOrder: this.sortOrder,
            alphabetFilter: this.alphabetFilter
        });
        
        if (this.autoLoad) {
            console.log('Auto-loading batch info...');
            await this.loadBatchInfo();
        } else {
            console.log('Auto-load disabled');
        }
    },
    methods: {
        async loadBatchInfo() {
            this.loading = true;
            this.error = null;
            
            console.log('Loading batch info with params:', {
                get_batch_info: 1,
                batch_size: this.batchSize,
                status: 'active',
                sort_order: this.sortOrder,
                alphabet_filter: this.alphabetFilter
            });
            
            try {
                const response = await window.axios.get('vocabularies', {
                    params: {
                        get_batch_info: 1,
                        batch_size: this.batchSize,
                        status: 'active',
                        sort_order: this.sortOrder,
                        alphabet_filter: this.alphabetFilter
                    }
                });
                
                console.log('Batch info response:', response.data);
                
                if (response?.data?.data) {
                    this.batches = response.data.data.batches || [];
                    console.log('Available batches:', this.batches);
                    
                    if (this.batches.length === 0) {
                        console.log('No batches available for current filter');
                        this.currentBatchInfo = null;
                        this.selectedBatch = '';
                        this.$emit('batch-loaded', [], null);
                        return;
                    }
                    
                    // Auto-select first batch if none selected AND batches exist
                    if (!this.selectedBatch && this.batches.length > 0) {
                        this.selectedBatch = this.batches[0].batch_number;
                        console.log('Auto-selecting first batch:', this.selectedBatch);
                        // Force immediate load without waiting
                        setTimeout(() => {
                            this.loadBatch(this.selectedBatch);
                        }, 100);
                    } else if (this.selectedBatch && this.batches.length > 0) {
                        // If a batch was already selected, reload it with new filters
                        console.log('Reloading existing batch with new filters:', this.selectedBatch);
                        // Reset and reload the first batch for new filters
                        this.selectedBatch = this.batches[0].batch_number;
                        setTimeout(() => {
                            this.loadBatch(this.selectedBatch);
                        }, 100);
                    }
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('Failed to load batch info:', error);
                this.error = error?.response?.data?.message || error.message || 'Failed to load vocabulary batches';
                this.$emit('batch-error', this.error);
            } finally {
                this.loading = false;
            }
        },
        
        async loadBatch(batchNumber) {
            if (!batchNumber) return;
            
            this.loading = true;
            this.error = null;
            
            console.log('Loading batch with params:', {
                batch_number: batchNumber,
                batch_size: this.batchSize,
                status: 'active',
                sort_order: this.sortOrder,
                alphabet_filter: this.alphabetFilter
            });
            
            try {
                // Check cache first (include sorting/filtering in cache key)
                const cacheKey = `batch_${batchNumber}_${this.batchSize}_${this.sortOrder}_${this.alphabetFilter}`;
                console.log('Cache key:', cacheKey);
                
                if (this.cache.has(cacheKey)) {
                    console.log('Using cached data for batch:', batchNumber);
                    const cached = this.cache.get(cacheKey);
                    this.currentBatchInfo = cached.batch_info;
                    this.$emit('batch-loaded', cached.data, cached.batch_info);
                    this.loading = false;
                    return;
                }
                
                const response = await window.axios.get('vocabularies', {
                    params: {
                        batch_number: batchNumber,
                        batch_size: this.batchSize,
                        status: 'active',
                        sort_order: this.sortOrder,
                        alphabet_filter: this.alphabetFilter
                    }
                });
                
                console.log('Batch data response:', response.data);
                
                if (response?.data?.data) {
                    const data = response.data.data.data || [];
                    const batchInfo = response.data.data.batch_info;
                    
                    console.log('Loaded vocabulary data:', data);
                    console.log('Batch info:', batchInfo);
                    
                    this.currentBatchInfo = batchInfo;
                    
                    // Cache the result
                    this.cache.set(cacheKey, { data, batch_info: batchInfo });
                    
                    // Emit immediately
                    this.$emit('batch-loaded', data, batchInfo);
                } else {
                    throw new Error('Invalid batch data format');
                }
            } catch (error) {
                console.error('Failed to load batch:', error);
                this.error = error?.response?.data?.message || error.message || 'Failed to load vocabulary batch';
                this.$emit('batch-error', this.error);
            } finally {
                this.loading = false;
            }
        },
        
        async handleBatchChange() {
            if (this.selectedBatch) {
                this.$emit('batch-selected', parseInt(this.selectedBatch));
                await this.loadBatch(this.selectedBatch);
            }
        },
        
        async goToPreviousBatch() {
            if (this.currentBatchInfo && this.currentBatchInfo.current_batch > 1) {
                this.selectedBatch = this.currentBatchInfo.current_batch - 1;
                await this.handleBatchChange();
            }
        },
        
        async goToNextBatch() {
            if (this.currentBatchInfo && this.currentBatchInfo.current_batch < this.currentBatchInfo.total_batches) {
                this.selectedBatch = this.currentBatchInfo.current_batch + 1;
                await this.handleBatchChange();
            }
        },
        
        async refreshBatches() {
            this.cache.clear(); // Clear cache on refresh
            this.selectedBatch = '';
            this.currentBatchInfo = null;
            await this.loadBatchInfo();
        },
        
        // Public methods for external control
        async selectBatch(batchNumber) {
            this.selectedBatch = batchNumber;
            await this.handleBatchChange();
        },
        
        getCurrentBatch() {
            return this.currentBatchInfo;
        },
        
        clearCache() {
            this.cache.clear();
        },
        
        // Handle sorting/filtering changes with debouncing
        debouncedHandleSortingChange() {
            if (this.sortingChangeTimer) {
                clearTimeout(this.sortingChangeTimer);
            }
            
            this.sortingChangeTimer = setTimeout(() => {
                this.handleSortingChange();
            }, 400);
        },
        
        // Handle sorting/filtering changes
        async handleSortingChange() {
            console.log('Handling sorting change:', {
                sortOrder: this.sortOrder,
                alphabetFilter: this.alphabetFilter
            });
            
            this.cache.clear(); // Clear cache when sorting/filtering changes
            this.selectedBatch = '';
            this.currentBatchInfo = null;
            await this.loadBatchInfo();
        }
    },
    
    beforeDestroy() {
        // Clean up timers
        if (this.sortingChangeTimer) {
            clearTimeout(this.sortingChangeTimer);
        }
    }
};
</script>

<style scoped>
.vocabulary-batch-selector {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-select:disabled {
    background-color: #f8f9fa;
    opacity: 0.65;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.btn-group .btn:last-child {
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
}

.progress {
    background-color: rgba(255,255,255,0.3);
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
}

.alert {
    border: none;
    border-radius: 8px;
    font-weight: 500;
}

.alert-info {
    background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%);
    border-left: 4px solid #0dcaf0;
}

.alert-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
    border-left: 4px solid #dc3545;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .vocabulary-batch-selector .row > div {
        margin-bottom: 1rem;
    }
    
    .vocabulary-batch-selector .row > div:last-child {
        margin-bottom: 0;
    }
    
    .form-select {
        min-width: 100% !important;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem !important;
    }
    
    .d-flex.gap-2 {
        justify-content: flex-start !important;
    }
}

/* Hover effects */
.form-select:hover:not(:disabled) {
    border-color: #6c757d;
}

.btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Focus accessibility */
.form-select:focus,
.btn:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
}
</style>
