export const initialState = {
    // Search input (optional to keep here for convenience)
    searchQuery: '',
    // 'e2b' = English -> Bangla, 'b2e' = Bangla -> English
    searchDirection: 'e2b',
    // Suggestion list shown by autocomplete
    suggestions: [] as any[],
    // Loading flag for async requests
    loading: false,
    // Word of the day object
    wordOfDay: null as null | { english?: string; bangla?: string; meaning?: string; example?: string },
    // Popular words collection
    popularWords: [] as any[],
    // Generic place to store errors (optional)
    error: null as null | string,
    
    // Batch management for large vocabulary lists
    vocabularyBatches: {
        currentBatch: 1,
        batchSize: 100,
        totalBatches: 0,
        totalCount: 0,
        availableBatches: [] as any[],
        currentBatchData: [] as any[],
        batchInfo: null as null | any,
        loadingBatch: false,
        batchError: null as null | string,
        cache: new Map() // Cache for storing loaded batches
    }
};

export default initialState;
