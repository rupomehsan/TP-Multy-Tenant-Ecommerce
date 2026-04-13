<template>
    <div class="vocabulary-sorting-panel">
        <div class="row align-items-center">
            <!-- Sort Order Controls -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <label class="form-label mb-0 fw-semibold">
                        <i class="fas fa-sort me-2"></i>
                        Sort Order:
                    </label>
                    <div class="btn-group" role="group">
                        <button 
                            @click="setSortOrder('asc')"
                            :class="['btn', sortOrder === 'asc' ? 'btn-primary' : 'btn-outline-primary']"
                            :disabled="isProcessing"
                            title="Ascending (A to Z)"
                        >
                            <i class="fas fa-sort-alpha-down me-1"></i>
                            A → Z
                            <span v-if="isProcessing && sortOrder === 'asc'" class="spinner-border spinner-border-sm ms-1"></span>
                        </button>
                        <button 
                            @click="setSortOrder('desc')"
                            :class="['btn', sortOrder === 'desc' ? 'btn-primary' : 'btn-outline-primary']"
                            :disabled="isProcessing"
                            title="Descending (Z to A)"
                        >
                            <i class="fas fa-sort-alpha-up me-1"></i>
                            Z → A
                            <span v-if="isProcessing && sortOrder === 'desc'" class="spinner-border spinner-border-sm ms-1"></span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Alphabet Filter -->
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <label class="form-label mb-0 fw-semibold">
                        <i class="fas fa-filter me-2"></i>
                        Filter by Letter:
                    </label>
                    <div class="d-flex gap-1 flex-wrap">
                        <!-- All Button -->
                        <button 
                            @click="setAlphabetFilter('')"
                            :class="['btn btn-sm', !alphabetFilter ? 'btn-success' : 'btn-outline-secondary']"
                            :disabled="isProcessing"
                            title="Show all words"
                        >
                            All
                            <span v-if="isProcessing && !alphabetFilter" class="spinner-border spinner-border-sm ms-1"></span>
                        </button>
                        
                        <!-- Alphabet Buttons -->
                        <button 
                            v-for="letter in alphabet" 
                            :key="letter"
                            @click="setAlphabetFilter(letter)"
                            :class="[
                                'btn btn-sm alphabet-btn', 
                                alphabetFilter === letter ? 'btn-primary' : (availableLetters.includes(letter) ? 'btn-outline-primary' : 'btn-outline-secondary'),
                                !availableLetters.includes(letter) ? 'disabled-letter' : ''
                            ]"
                            :disabled="isProcessing"
                            :title="availableLetters.includes(letter) ? `Show words starting with ${letter}` : `No words available starting with ${letter}`"
                        >
                            {{ letter }}
                            <span v-if="isProcessing && alphabetFilter === letter" class="spinner-border spinner-border-sm ms-1"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Active Filters Display -->
        <div v-if="alphabetFilter || sortOrder !== 'asc'" class="mt-3">
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Active filters:</span>
                    <span v-if="alphabetFilter" class="badge bg-primary">
                        Letter: {{ alphabetFilter }}
                    </span>
                    <span class="badge bg-secondary">
                        Order: {{ sortOrder === 'asc' ? 'A → Z' : 'Z → A' }}
                    </span>
                </div>
                <button 
                    @click="clearAllFilters"
                    class="btn btn-sm btn-outline-danger"
                    :disabled="isProcessing"
                    title="Clear all filters"
                >
                    <i class="fas fa-times me-1"></i>
                    Clear All
                    <span v-if="isProcessing" class="spinner-border spinner-border-sm ms-1"></span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Responsive Alphabet Grid -->
        <div class="d-md-none mt-3" v-if="showMobileAlphabet">
            <div class="alphabet-grid">
                <button 
                    @click="setAlphabetFilter('')"
                    :class="['btn btn-sm', !alphabetFilter ? 'btn-success' : 'btn-outline-secondary']"
                >
                    All
                </button>
                <button 
                    v-for="letter in alphabet" 
                    :key="letter"
                    @click="setAlphabetFilter(letter)"
                    :class="[
                        'btn btn-sm', 
                        alphabetFilter === letter ? 'btn-primary' : (availableLetters.includes(letter) ? 'btn-outline-primary' : 'btn-outline-secondary'),
                        !availableLetters.includes(letter) ? 'disabled-letter' : ''
                    ]"
                    :disabled="isProcessing"
                    :title="availableLetters.includes(letter) ? `Show words starting with ${letter}` : `No words available starting with ${letter}`"
                >
                    {{ letter }}
                    <span v-if="isProcessing && alphabetFilter === letter" class="spinner-border spinner-border-sm ms-1"></span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Toggle Button -->
        <div class="d-md-none mt-2">
            <button 
                @click="showMobileAlphabet = !showMobileAlphabet"
                class="btn btn-outline-secondary btn-sm w-100"
            >
                <i class="fas" :class="showMobileAlphabet ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                {{ showMobileAlphabet ? 'Hide' : 'Show' }} Alphabet Filter
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'VocabularySortingPanel',
    props: {
        initialSortOrder: {
            type: String,
            default: 'asc'
        },
        initialAlphabetFilter: {
            type: String,
            default: ''
        }
    },
    emits: ['sort-changed', 'filter-changed', 'filters-cleared'],
    data() {
        return {
            sortOrder: this.initialSortOrder,
            alphabetFilter: this.initialAlphabetFilter,
            showMobileAlphabet: false,
            isProcessing: false, // Add loading state
            debounceTimer: null, // For debouncing
            alphabet: [
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 
                'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 
                'U', 'V', 'W', 'X', 'Y', 'Z'
            ],
            availableLetters: ['A', 'C', 'D', 'F', 'P', 'R', 'S', 'U'] // Letters that have vocabulary data
        };
    },
    watch: {
        initialSortOrder(newVal) {
            this.sortOrder = newVal;
        },
        initialAlphabetFilter(newVal) {
            this.alphabetFilter = newVal;
        }
    },
    methods: {
        setSortOrder(order) {
            if (this.isProcessing || this.sortOrder === order) return;
            
            this.isProcessing = true;
            this.sortOrder = order;
            this.debouncedEmitChanges();
        },
        
        setAlphabetFilter(letter) {
            if (this.isProcessing || this.alphabetFilter === letter) return;
            
            // If letter has no available words, show a warning but still allow the filter
            if (letter && !this.availableLetters.includes(letter)) {
                // You could show a toast notification here if available
                console.warn(`No words available starting with "${letter}"`);
            }
            
            this.isProcessing = true;
            this.alphabetFilter = letter;
            this.debouncedEmitChanges();
        },
        
        clearAllFilters() {
            if (this.isProcessing) return;
            
            this.isProcessing = true;
            this.sortOrder = 'asc';
            this.alphabetFilter = '';
            this.debouncedEmitChanges();
            this.$emit('filters-cleared');
        },
        
        debouncedEmitChanges() {
            // Clear existing timer
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer);
            }
            
            // Set new timer with 300ms delay
            this.debounceTimer = setTimeout(() => {
                this.emitChanges();
                this.isProcessing = false;
            }, 300);
        },
        
        emitChanges() {
            this.$emit('sort-changed', this.sortOrder);
            this.$emit('filter-changed', this.alphabetFilter);
        },
        
        // Public methods for external control
        getSortOrder() {
            return this.sortOrder;
        },
        
        getAlphabetFilter() {
            return this.alphabetFilter;
        },
        
        updateFilters(sortOrder, alphabetFilter) {
            this.sortOrder = sortOrder || 'asc';
            this.alphabetFilter = alphabetFilter || '';
        }
    },
    
    beforeDestroy() {
        // Clean up timer on component destroy
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }
    }
};
</script>

<style scoped>
.vocabulary-sorting-panel {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

.alphabet-btn {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.disabled-letter {
    opacity: 0.5;
    cursor: not-allowed !important;
}

.disabled-letter:hover {
    transform: none !important;
    box-shadow: none !important;
}

.alphabet-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(45px, 1fr));
    gap: 6px;
    max-width: 100%;
}

.alphabet-grid .btn {
    min-width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
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

.badge {
    font-size: 0.75rem;
    padding: 0.4em 0.6em;
    border-radius: 4px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .vocabulary-sorting-panel .row > div {
        margin-bottom: 1rem;
    }
    
    .vocabulary-sorting-panel .row > div:last-child {
        margin-bottom: 0;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem !important;
    }
    
    .d-flex.gap-1 {
        display: none !important; /* Hide desktop alphabet buttons on mobile */
    }
}

@media (max-width: 576px) {
    .vocabulary-sorting-panel {
        padding: 1rem;
    }
    
    .alphabet-grid {
        grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
        gap: 4px;
    }
    
    .alphabet-grid .btn {
        min-width: 40px;
        height: 40px;
        font-size: 0.8rem;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
}

/* Focus accessibility */
.btn:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Hover effects for alphabet buttons */
.alphabet-btn:hover:not(:disabled) {
    background-color: var(--bs-primary) !important;
    color: white !important;
    border-color: var(--bs-primary) !important;
}

/* Active state animations */
.btn.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-color: #0d6efd;
}

.btn.btn-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    border-color: #198754;
}

/* Loading state for buttons */
.btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none !important;
}

/* Processing state overlay */
.vocabulary-sorting-panel.processing {
    pointer-events: none;
    opacity: 0.8;
}

/* Spinner styles */
.spinner-border-sm {
    width: 0.75rem;
    height: 0.75rem;
    border-width: 0.1em;
}

/* Print styles */
@media print {
    .vocabulary-sorting-panel {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        background: white !important;
    }
    
    .btn {
        border: 1px solid #000 !important;
        background: white !important;
        color: black !important;
    }
}
</style>
