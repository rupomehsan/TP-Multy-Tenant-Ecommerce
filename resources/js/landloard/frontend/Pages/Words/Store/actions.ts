export const actions = {
    // Fetch autocomplete suggestions from backend
    async fetchSuggestions(this: any, query: string, direction = 'e2b') {
        
        if (!query || query.trim().length === 0) {
            this.suggestions = [];
            return;
        }

        this.loading = true;
        this.error = null;

        try {
            const q = encodeURIComponent(query.trim());
            const response = await (window as any).axios.get(`vocabularies?get_all=1&q=${q}&direction=${direction}`);

            if (response && response.data) {
                this.suggestions = (response.data.data || response.data).map((item: any, idx: number) => ({
                    id: item.id ?? idx,
                    word: direction === 'e2b' ? (item.english ?? item.word) : (item.bangla ?? item.word),
                    meaning: direction === 'e2b' ? (item.bangla ?? item.meaning) : (item.english ?? item.meaning),
                    raw: item,
                }));
            } else {
                this.suggestions = [];
            }
        } catch (err: any) {
            this.error = err?.message || 'Failed to fetch suggestions';
            this.suggestions = [];
        } finally {
            this.loading = false;
        }
    },

    // Get Word of the Day
    async fetchWordOfDay(this: any) {
        this.loading = true;
        this.error = null;
        try {
            const response = await (window as any).axios.get('word-of-day');
            if (response && response.data) {
                this.wordOfDay = response.data.data || response.data;
            }
        } catch (err: any) {
            this.error = err?.message || 'Failed to fetch word of the day';
        } finally {
            this.loading = false;
        }
    },

    // Get popular words
    async fetchPopularWords(this: any) {
        this.loading = true;
        this.error = null;
        try {
            const response = await (window as any).axios.get('popular-words');
            if (response && response.data) {
                this.popularWords = response.data.data || response.data;
            }
        } catch (err: any) {
            this.error = err?.message || 'Failed to fetch popular words';
        } finally {
            this.loading = false;
        }
    },

    // Batch Management Actions
    async fetchBatchInfo(this: any, batchSize = 100) {
        this.vocabularyBatches.loadingBatch = true;
        this.vocabularyBatches.batchError = null;
        
        try {
            const response = await (window as any).axios.get('vocabularies', {
                params: {
                    get_batch_info: 1,
                    batch_size: batchSize,
                    status: 'active'
                }
            });
            
            if (response?.data?.data) {
                const data = response.data.data;
                this.vocabularyBatches.totalCount = data.total_count;
                this.vocabularyBatches.totalBatches = data.total_batches;
                this.vocabularyBatches.batchSize = data.batch_size;
                this.vocabularyBatches.availableBatches = data.batches || [];
                
                return data;
            } else {
                throw new Error('Invalid batch info response');
            }
        } catch (err: any) {
            this.vocabularyBatches.batchError = err?.message || 'Failed to fetch batch information';
            throw err;
        } finally {
            this.vocabularyBatches.loadingBatch = false;
        }
    },

    async fetchVocabularyBatch(this: any, batchNumber: number, batchSize = 100) {
        const cacheKey = `batch_${batchNumber}_${batchSize}`;
        
        // Check cache first
        if (this.vocabularyBatches.cache.has(cacheKey)) {
            const cached = this.vocabularyBatches.cache.get(cacheKey);
            this.vocabularyBatches.currentBatchData = cached.data;
            this.vocabularyBatches.batchInfo = cached.batch_info;
            this.vocabularyBatches.currentBatch = batchNumber;
            return cached;
        }
        
        this.vocabularyBatches.loadingBatch = true;
        this.vocabularyBatches.batchError = null;
        
        try {
            const response = await (window as any).axios.get('vocabularies', {
                params: {
                    batch_number: batchNumber,
                    batch_size: batchSize,
                    status: 'active'
                }
            });
            
            if (response?.data?.data) {
                const data = response.data.data.data || [];
                const batchInfo = response.data.data.batch_info;
                
                // Update state
                this.vocabularyBatches.currentBatchData = data;
                this.vocabularyBatches.batchInfo = batchInfo;
                this.vocabularyBatches.currentBatch = batchNumber;
                
                // Cache the result
                this.vocabularyBatches.cache.set(cacheKey, { data, batch_info: batchInfo });
                
                return { data, batch_info: batchInfo };
            } else {
                throw new Error('Invalid batch data response');
            }
        } catch (err: any) {
            this.vocabularyBatches.batchError = err?.message || 'Failed to fetch vocabulary batch';
            throw err;
        } finally {
            this.vocabularyBatches.loadingBatch = false;
        }
    },

    setBatchSize(this: any, size: number) {
        this.vocabularyBatches.batchSize = size;
        // Clear cache when batch size changes
        this.vocabularyBatches.cache.clear();
    },

    clearBatchCache(this: any) {
        this.vocabularyBatches.cache.clear();
    },

    // Navigation helpers
    async goToNextBatch(this: any) {
        if (this.vocabularyBatches.currentBatch < this.vocabularyBatches.totalBatches) {
            await this.fetchVocabularyBatch(this.vocabularyBatches.currentBatch + 1, this.vocabularyBatches.batchSize);
        }
    },

    async goToPreviousBatch(this: any) {
        if (this.vocabularyBatches.currentBatch > 1) {
            await this.fetchVocabularyBatch(this.vocabularyBatches.currentBatch - 1, this.vocabularyBatches.batchSize);
        }
    },

    // Get batch by range (e.g., "1-100", "101-200")
    getBatchByRange(this: any, range: string) {
        return this.vocabularyBatches.availableBatches.find((batch: any) => batch.label === range);
    }
};

export default actions;
