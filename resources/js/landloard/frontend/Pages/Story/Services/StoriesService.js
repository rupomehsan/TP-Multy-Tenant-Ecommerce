/**
 * Stories Service
 * Handles API calls for vocabulary stories with pagination support
 */

export class StoriesService {
    /**
     * Fetch all stories with pagination support
     * @param {Object} params - Query parameters
     * @param {number} params.limit - Number of stories per page (default: 20)
     * @param {number} params.page - Page number (default: 1)
     * @param {string} params.sort_by_col - Column to sort by (default: 'id')
     * @param {string} params.sort_type - Sort direction (default: 'asc')
     * @param {number} params.get_all - Get all data without pagination (1 or 0)
     * @returns {Promise} API response with stories data
     */
    static async fetchStories(params = {}) {
        const defaultParams = {
            limit: 20,
            page: 1,
            sort_by_col: 'id',
            sort_type: 'asc',
            status: 'active',
            get_all: 0
        };

        const queryParams = { ...defaultParams, ...params };
        
        try {
            // Build query string
            const queryString = new URLSearchParams(queryParams).toString();
            const response = await window.publicAxios(`vocabolary-stories?${queryString}`, 'get');
            
            if (response && response.status === 'success') {
                return {
                    success: true,
                    data: response.data.data || [],
                    totalStories: response.data.total || 0,
                    currentPage: response.data.current_page || 1,
                    perPage: response.data.per_page || 20,
                    lastPage: response.data.last_page || 1,
                    counts: {
                        active: response.data.active_data_count || 0,
                        inactive: response.data.inactive_data_count || 0,
                        trashed: response.data.trashed_data_count || 0
                    }
                };
            } else {
                return {
                    success: false,
                    error: response?.message || 'Failed to fetch stories',
                    data: []
                };
            }
        } catch (error) {
            console.error('Stories Service Error:', error);
            return {
                success: false,
                error: error.message || 'Network error occurred',
                data: []
            };
        }
    }

    /**
     * Fetch a single story by slug
     * @param {string} slug - Story slug
     * @returns {Promise} API response with single story data
     */
    static async fetchStory(slug) {
        try {
            const response = await window.publicAxios(`vocabolary-stories/${slug}`, 'get');
            
            if (response && response.status === 'success') {
                return {
                    success: true,
                    data: response.data
                };
            } else {
                return {
                    success: false,
                    error: response?.message || 'Story not found',
                    data: null
                };
            }
        } catch (error) {
            console.error('Story Fetch Error:', error);
            return {
                success: false,
                error: error.message || 'Network error occurred',
                data: null
            };
        }
    }

    /**
     * Calculate range options based on total stories
     * @param {number} totalStories - Total number of stories
     * @param {number} rangeSize - Stories per range (default: 20)
     * @returns {Array} Array of range objects
     */
    static calculateRanges(totalStories, rangeSize = 20) {
        const ranges = [];
        let start = 1;
        
        while (start <= totalStories) {
            const end = Math.min(start + rangeSize - 1, totalStories);
            ranges.push({
                label: `${start}–${end}`,
                start: start,
                end: end,
                page: Math.ceil(start / rangeSize)
            });
            start += rangeSize;
        }
        
        return ranges;
    }

    /**
     * Get the range that contains a specific story number
     * @param {number} storyNumber - Story number (1-based)
     * @param {number} rangeSize - Stories per range (default: 20)
     * @returns {Object} Range object containing the story number
     */
    static getRangeForStory(storyNumber, rangeSize = 20) {
        const rangeIndex = Math.ceil(storyNumber / rangeSize);
        const start = (rangeIndex - 1) * rangeSize + 1;
        const end = rangeIndex * rangeSize;
        
        return {
            label: `${start}–${end}`,
            start: start,
            end: end,
            page: rangeIndex
        };
    }
}