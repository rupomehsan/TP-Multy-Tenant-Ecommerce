<template>
  <div class="vocabulary-page">
    <!-- Header Section -->
    <div class="hero-section bg-primary text-white">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold mb-4">
              <i class="fas fa-book-open me-3"></i>
              Vocabulary Explorer
            </h1>
            <p class="lead mb-4">Explore our comprehensive vocabulary collection with smart batch navigation</p>
          </div>
          <Link href="/vocabulary-test" class="mx-auto text-center" style="text-decoration: none;">
            <button data-v-d691716a="" class="language-toggle-btn active">
              <div data-v-d691716a="" class="language-display">
                <span data-v-d691716a="" class="from-lang"><i data-v-d691716a="" class="fas fa-flag-usa"></i> Participate in a Vocabulary Evaluation</span>
              </div>
            </button>
          </Link>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
      <!-- Debug Info (remove in production) -->

      <!-- Vocabulary Sorting Panel -->
      <VocabularySortingPanel
        :initial-sort-order="sortOrder"
        :initial-alphabet-filter="alphabetFilter"
        @sort-changed="handleSortChanged"
        @filter-changed="handleFilterChanged"
        @filters-cleared="handleFiltersCleared"
        ref="sortingPanel"
      />

      <!-- Search Section -->
      <div class="search-section mb-4" v-if="showSearch">
        <div class="row">
          <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="input-group input-group-lg">
                  <button
                    @click="toggleDirection"
                    class="btn btn-outline-secondary direction-toggle"
                    :title="searchDirection === 'e2b' ? 'English to Bangla' : 'Bangla to English'"
                  >
                    {{ searchDirection === "e2b" ? "EN→বাং" : "বাং→EN" }}
                  </button>
                  <input
                    v-model="searchQuery"
                    @input="handleSearch"
                    @focus="showSuggestions = true"
                    @blur="hideSuggestions"
                    type="text"
                    class="form-control"
                    :placeholder="searchDirection === 'e2b' ? 'Search English words...' : 'Search Bangla words...'"
                  />
                  <button @click="performSearch" class="btn btn-primary" :disabled="!searchQuery.trim()">
                    <i class="fas fa-search"></i>
                  </button>
                </div>

                <!-- Autocomplete Suggestions -->
                <div
                  v-if="showSuggestions && homeStore?.suggestions?.length"
                  class="suggestions-dropdown position-absolute w-100 bg-white border rounded-bottom shadow-lg mt-1"
                >
                  <div
                    v-for="suggestion in homeStore.suggestions.slice(0, 8)"
                    :key="suggestion.id"
                    @click="selectSuggestion(suggestion)"
                    class="suggestion-item p-3 border-bottom"
                  >
                    <div class="fw-semibold">{{ suggestion.word }}</div>
                    <div class="text-muted small">{{ suggestion.meaning }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Vocabulary Results -->
      <div class="vocabulary-results">
        <!-- Search Results (when searching) -->
        <div v-if="searchResults.length > 0" class="search-results-section">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
              <i class="fas fa-search me-2 text-primary"></i>
              Search Results
            </h3>
            <button @click="clearSearch" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-times me-1"></i>
              Clear Search
            </button>
          </div>
          <div class="row">
            <div v-for="word in searchResults" :key="word.id" class="col-12 mb-3">
              <VocabularyCard
                :word="word"
                :is-expanded="isExpanded(word.id)"
                :is-in-revision="isInRevision(word.id)"
                @toggle-details="toggleWordDetails"
                @revision-updated="handleRevisionUpdate"
                @keyword-click="onKeywordClick"
              />
            </div>
          </div>
        </div>

        <!-- Batch Results (cached data) -->
        <div v-else-if="cacheLoaded && currentBatchData.length > 0" class="batch-results-section">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>
              <i class="fas fa-list me-2 text-primary"></i>
              Vocabulary List
              <span v-if="currentBatchInfo" class="badge bg-secondary ms-2">
                {{ currentBatchInfo.start_index }}-{{ currentBatchInfo.end_index }}
              </span>
              <span v-if="alphabetFilter" class="badge bg-primary ms-1"> Letter: {{ alphabetFilter }} </span>
              <span class="badge bg-info ms-1">
                {{ sortOrder === "asc" ? "A → Z" : "Z → A" }}
              </span>
            </h3>
            <div class="d-flex gap-2">
              <button @click="showSearch = !showSearch" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-search me-1"></i>
                {{ showSearch ? "Hide Search" : "Show Search" }}
              </button>
              <button @click="refreshBatch" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-refresh me-1"></i>
                Refresh
              </button>
            </div>
          </div>
          <div class="row">
            <div v-for="word in currentBatchData" :key="word.id" class="col-12 mb-3">
              <VocabularyCard
                :word="word"
                :is-expanded="isExpanded(word.id)"
                :is-in-revision="isInRevision(word.id)"
                @toggle-details="toggleWordDetails"
                @revision-updated="handleRevisionUpdate"
                @keyword-click="onKeywordClick"
              />
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="cacheLoaded" class="empty-state text-center py-5">
          <div class="alert alert-warning">
            ⚠️ No vocabulary data found for current filters. currentBatchData has {{ currentBatchData.length }} items.
            <br />
            <span v-if="alphabetFilter"> No words starting with "{{ alphabetFilter }}" found. </span>
          </div>
          <div class="mb-4">
            <i class="fas fa-book-open fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted">
            {{ alphabetFilter ? `No words starting with "${alphabetFilter}"` : "No vocabulary found" }}
          </h4>
          <p class="text-muted">
            {{
              alphabetFilter
                ? `Try selecting a different letter. Available letters: A, C, D, F, P, R, S, U`
                : "Select a batch to start exploring vocabulary"
            }}
          </p>
          <button v-if="alphabetFilter" @click="clearAlphabetFilter" class="btn btn-outline-primary">
            <i class="fas fa-times me-1"></i>
            Clear Letter Filter
          </button>
        </div>

        <!-- Loading State -->
        <div v-if="!cacheLoaded" class="loading-state text-center py-5">
          <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem"></div>
          <h5 class="text-muted">Loading vocabulary from server...</h5>
          <p class="text-muted">Building cache for faster access...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { store as useHomeStore } from "./Store";
import VocabularyBatchSelector from "./Components/VocabularyBatchSelector.vue";
import VocabularyCard from "./Components/VocabularyCard.vue";
import VocabularySortingPanel from "./Components/VocabularySortingPanel.vue";

export default {
  name: "VocabularyPage",
  components: {
    VocabularyBatchSelector,
    VocabularyCard,
    VocabularySortingPanel,
  },
  data() {
    return {
      // Search related
      searchQuery: "",
      searchDirection: "e2b",
      searchResults: [],
      showSuggestions: false,
      showSearch: false,

      // Caching system
      allVocabularyCache: [], // Cache all vocabulary data
      cacheLoaded: false,
      cacheLoadingTime: null,

      // Sorting and filtering
      sortOrder: "asc",
      alphabetFilter: "",

      // Rate limiting
      lastSortingChangeTime: 0,
      sortingChangeInProgress: false,

      // UI state
      expandedWords: new Set(),
      favorites: new Set(),
      revisions: new Set(),

      // Store instance
      homeStore: null,
    };
  },
  async created() {
    console.log("VocabularyPage created - starting initialization");
    this.homeStore = useHomeStore();
    console.log("Home store loaded:", this.homeStore);
    await this.initializePage();
    console.log("Page initialization complete");
  },
  computed: {
    // Debug computed to see current state
    debugInfo() {
      return {
        currentBatchData: this.currentBatchData,
        currentBatchInfo: this.currentBatchInfo,
        searchResults: this.searchResults,
        showSearch: this.showSearch,
        alphabetFilter: this.alphabetFilter,
        sortOrder: this.sortOrder,
        cacheLoaded: this.cacheLoaded,
        allVocabularyCacheLength: this.allVocabularyCache.length,
      };
    },

    // Filtered vocabulary based on current filters
    filteredVocabulary() {
      if (!this.cacheLoaded || this.allVocabularyCache.length === 0) {
        return [];
      }

      let filtered = [...this.allVocabularyCache];

      // Apply alphabet filter
      if (this.alphabetFilter) {
        console.log(`Filtering by letter: "${this.alphabetFilter}"`);
        filtered = filtered.filter((word) => {
          const firstLetter = word.word.charAt(0).toLowerCase();
          const filterLetter = this.alphabetFilter.toLowerCase();
          const matches = firstLetter === filterLetter;
          console.log(`Word "${word.word}" starts with "${firstLetter}", filter is "${filterLetter}", matches: ${matches}`);
          return matches;
        });
        console.log(`After filtering by "${this.alphabetFilter}": ${filtered.length} words found`);
      }

      // Apply sorting
      filtered.sort((a, b) => {
        const comparison = a.word.localeCompare(b.word);
        return this.sortOrder === "asc" ? comparison : -comparison;
      });

      return filtered;
    },

    // Current batch data (reactive computed property)
    currentBatchData() {
      const data = this.filteredVocabulary;
      console.log(`currentBatchData computed: ${data.length} items`);
      return data;
    },

    // Batch info computed from current data
    currentBatchInfo() {
      if (!this.cacheLoaded) return null;

      return {
        current_batch: 1,
        batch_size: this.currentBatchData.length,
        total_count: this.filteredVocabulary.length,
        total_batches: 1,
        start_index: 1,
        end_index: this.currentBatchData.length,
        alphabet_filter: this.alphabetFilter,
        sort_order: this.sortOrder,
      };
    },
  },
  methods: {
    async initializePage() {
      console.log("Initializing vocabulary page...");
      // Load favorites from localStorage
      this.loadFavorites();
      // Load revisions from localStorage  
      this.loadRevisions();

      // Load all vocabulary data into cache
      await this.loadAllVocabularyData();

      // Initialize display with cached data
      this.updateCurrentBatchFromCache();

      console.log("Page initialization complete - using cached data");
    },

    // Cache Management Methods
    async loadAllVocabularyData() {
      // First try to load from localStorage
      if (this.loadCacheFromLocalStorage()) {
        this.updateCurrentBatchFromCache();
        console.log("✅ Using cached data from localStorage");
        return;
      }

      console.log("Loading all vocabulary data from API...");
      const startTime = Date.now();

      try {
        const response = await window.axios.get("vocabularies", {
          params: {
            get_all: 1,
            limit: 1000, // Get all vocabulary
            status: "active",
          },
        });

        if (response?.data?.data) {
          this.allVocabularyCache = response.data.data;
          this.cacheLoaded = true;
          this.cacheLoadingTime = Date.now() - startTime;

          console.log(`✅ Cached ${this.allVocabularyCache.length} vocabulary items in ${this.cacheLoadingTime}ms`);

          // Save to localStorage for future visits
          this.saveCacheToLocalStorage();
        } else {
          throw new Error("Invalid response format");
        }
      } catch (error) {
        console.error("Failed to load vocabulary from API:", error);
        this.$toast?.error?.("Failed to load vocabulary data") || alert("Failed to load vocabulary data");
      }
    },

    saveCacheToLocalStorage() {
      try {
        const cacheData = {
          data: this.allVocabularyCache,
          timestamp: Date.now(),
          version: "1.0",
        };
        localStorage.setItem("vocabulary_cache", JSON.stringify(cacheData));
        console.log("✅ Vocabulary cache saved to localStorage");
      } catch (error) {
        console.warn("Failed to save cache to localStorage:", error);
      }
    },

    loadCacheFromLocalStorage() {
      try {
        const cachedData = localStorage.getItem("vocabulary_cache");
        if (cachedData) {
          const parsed = JSON.parse(cachedData);
          const cacheAge = Date.now() - parsed.timestamp;

          // Cache expires after 24 hours
          if (cacheAge < 24 * 60 * 60 * 1000) {
            this.allVocabularyCache = parsed.data || [];
            this.cacheLoaded = true;
            console.log(`✅ Loaded ${this.allVocabularyCache.length} items from localStorage cache`);
            return true;
          } else {
            console.log("Cache expired, will fetch fresh data");
            localStorage.removeItem("vocabulary_cache");
          }
        }
      } catch (error) {
        console.warn("Failed to load cache from localStorage:", error);
        localStorage.removeItem("vocabulary_cache");
      }
      return false;
    },

    // Batch Management Methods
    handleBatchSelected(batchNumber) {
      console.log("Batch selected:", batchNumber);
    },

    handleBatchLoaded(data, batchInfo) {
      console.log("Batch loaded in Index.vue:", { data, batchInfo });
      this.currentBatchData = data;
      this.currentBatchInfo = batchInfo;
      this.searchResults = []; // Clear search results when loading batch
    },

    handleBatchError(error) {
      console.error("Batch error:", error);
      this.$toast?.error?.(error) || alert("Error: " + error);
    },

    async refreshBatch() {
      if (this.$refs.batchSelector) {
        await this.$refs.batchSelector.refreshBatches();
      }
    },

    clearAlphabetFilter() {
      this.alphabetFilter = "";
      if (this.$refs.sortingPanel) {
        this.$refs.sortingPanel.updateFilters(this.sortOrder, "");
      }
    },

    // Sorting and Filtering Methods
    async handleSortChanged(newSortOrder) {
      if (this.sortingChangeInProgress) return;

      console.log("Sort order changed to:", newSortOrder);
      this.sortOrder = newSortOrder;
      // Computed properties will automatically update the display
    },

    async handleFilterChanged(newAlphabetFilter) {
      if (this.sortingChangeInProgress) return;

      console.log("Alphabet filter changed to:", newAlphabetFilter);
      this.alphabetFilter = newAlphabetFilter;
      // Computed properties will automatically update the display
    },

    async handleFiltersCleared() {
      if (this.sortingChangeInProgress) return;

      console.log("Clearing all filters");
      this.sortOrder = "asc";
      this.alphabetFilter = "";
      // Computed properties will automatically update the display
    },

    // Search Methods
    async handleSearch() {
      if (this.homeStore && this.searchQuery.length > 1) {
        await this.homeStore.fetchSuggestions(this.searchQuery, this.searchDirection);
        this.showSuggestions = true;
      } else {
        this.showSuggestions = false;
      }
    },

    async performSearch() {
      if (!this.searchQuery.trim()) return;

      this.showSuggestions = false;
      try {
        const response = await window.axios.get("vocabularies", {
          params: {
            get_all: 1,
            q: this.searchQuery,
            direction: this.searchDirection,
            sort_order: this.sortOrder,
            alphabet_filter: this.alphabetFilter,
            limit: 50,
          },
        });

        if (response?.data?.data) {
          this.searchResults = response.data.data;
        } else {
          this.searchResults = [];
        }
      } catch (error) {
        console.error("Search failed:", error);
        this.searchResults = [];
        this.$toast?.error?.("Search failed") || alert("Search failed");
      }
    },

    clearSearch() {
      this.searchQuery = "";
      this.searchResults = [];
      this.showSuggestions = false;
    },

    async fetchAllVocabukary() {
      // Legacy method - keeping for compatibility
      return this.performSearch();
    },

    async selectSuggestion(suggestion) {
      this.searchQuery = suggestion.word;
      this.showSuggestions = false;

      const slug = suggestion.slug || (suggestion.raw && suggestion.raw.slug);
      if (slug) {
        await this.fetchSingleWord(slug);
        return;
      }

      this.performSearch();
    },

    async fetchSingleWord(slug) {
      try {
        const response = await window.axios.get(`vocabularies/${slug}`);
        if (response?.data) {
          const payload = response.data.data || response.data;
          this.searchResults = [payload];
          if (this.searchResults[0]) {
            this.searchResults[0]._loadedDetails = true;
          }
        } else {
          this.searchResults = [];
        }
      } catch (err) {
        console.error("Failed to fetch single word:", err);
        this.searchResults = [];
      }
    },

    toggleDirection() {
      this.searchDirection = this.searchDirection === "e2b" ? "b2e" : "e2b";
      if (this.searchQuery.trim().length > 0) {
        this.handleSearch();
      } else if (this.homeStore) {
        this.homeStore.suggestions = [];
      }
    },

    hideSuggestions() {
      setTimeout(() => {
        this.showSuggestions = false;
      }, 200);
    },

    searchWord(word) {
      this.searchQuery = word;
      this.performSearch();
    },

    getNewWordOfDay() {
      if (this.homeStore) {
        this.homeStore.fetchWordOfDay();
      }
    },

    // Word details and favorites methods
    async toggleWordDetails(wordId) {
      const wordList = this.searchResults.length > 0 ? this.searchResults : this.currentBatchData;
      const idx = wordList.findIndex((w) => w.id === wordId);
      if (idx === -1) return;

      const word = wordList[idx];

      if (word._loadingDetails) return;

      if (!word._loadedDetails && word.slug) {
        this.$set ? this.$set(word, "_loadingDetails", true) : (word._loadingDetails = true);
        try {
          const response = await window.axios.get(`vocabularies/${word.slug}`);
          if (response?.data) {
            const payload = response.data.data || response.data;
            Object.keys(payload).forEach((key) => {
              if (["_loadingDetails", "_loadedDetails"].includes(key)) return;
              this.$set ? this.$set(word, key, payload[key]) : (word[key] = payload[key]);
            });
            this.$set ? this.$set(word, "_loadedDetails", true) : (word._loadedDetails = true);
          }
        } catch (err) {
          console.error("Failed to load word details:", err);
        } finally {
          this.$set ? this.$set(word, "_loadingDetails", false) : (word._loadingDetails = false);
        }
      }

      if (this.expandedWords.has(wordId)) {
        this.expandedWords.delete(wordId);
      } else {
        this.expandedWords.add(wordId);
      }
    },

    isExpanded(wordId) {
      return this.expandedWords.has(wordId);
    },

    toggleFavorite(word) {
      if (this.favorites.has(word.id)) {
        this.favorites.delete(word.id);
      } else {
        this.favorites.add(word.id);
      }
      this.saveFavorites();
    },

    isFavorite(wordId) {
      return this.favorites.has(wordId);
    },

    // Revision methods
    isInRevision(wordId) {
      return this.revisions.has(wordId);
    },

    handleRevisionUpdate(event) {
      console.log('Revision updated:', event);
      if (event.isInRevision) {
        this.revisions.add(event.wordId);
      } else {
        this.revisions.delete(event.wordId);
      }
      this.saveRevisions();
    },

    loadRevisions() {
      try {
        const saved = localStorage.getItem("dictionary_revisions");
        if (saved) {
          this.revisions = new Set(JSON.parse(saved));
        }
      } catch (error) {
        console.error("Failed to load revisions:", error);
      }
    },

    saveRevisions() {
      try {
        localStorage.setItem("dictionary_revisions", JSON.stringify([...this.revisions]));
      } catch (error) {
        console.error("Failed to save revisions:", error);
      }
    },

    loadFavorites() {
      try {
        const saved = localStorage.getItem("dictionary_favorites");
        if (saved) {
          this.favorites = new Set(JSON.parse(saved));
        }
      } catch (error) {
        console.error("Failed to load favorites:", error);
      }
    },

    saveFavorites() {
      try {
        localStorage.setItem("dictionary_favorites", JSON.stringify([...this.favorites]));
      } catch (error) {
        console.error("Failed to save favorites:", error);
      }
    },

    getSynonyms(synonymsString) {
      if (!synonymsString) return [];
      return synonymsString
        .split(",")
        .map((s) => s.trim())
        .filter((s) => s);
    },

    getAntonyms(antonymsString) {
      if (!antonymsString) return [];
      return antonymsString
        .split(",")
        .map((s) => s.trim())
        .filter((s) => s);
    },

    getExampleSentences(examples) {
      if (!examples) return [];
      if (typeof examples === "string") {
        try {
          return JSON.parse(examples);
        } catch {
          return [];
        }
      }
      return Array.isArray(examples) ? examples : [];
    },

    /**
     * Return story HTML with the matched word highlighted (red + bold).
     */
    getHighlightedStoryHtml(storyHtml, matchWord, selectedKeyword) {
      if (!storyHtml) return "";
      if (!matchWord && !selectedKeyword) return storyHtml;

      try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(storyHtml, "text/html");
        const target = selectedKeyword || matchWord;
        const walker = doc.createTreeWalker(doc.body, NodeFilter.SHOW_TEXT, null, false);
        const nodesToReplace = [];

        while (walker.nextNode()) {
          const node = walker.currentNode;
          if (node.nodeValue && /[A-Za-z]/.test(node.nodeValue)) {
            nodesToReplace.push(node);
          }
        }

        nodesToReplace.forEach((textNode) => {
          const text = textNode.nodeValue;
          const replaced = text.replace(/([A-Za-z]+)/g, (m) => {
            const isMatch = target && m.toLowerCase() === target.toLowerCase();
            const isSelected = selectedKeyword && m.toLowerCase() === selectedKeyword.toLowerCase();
            const classes = ["story-word"];
            if (isMatch) classes.push("matched-word");
            if (isSelected) classes.push("matched-clicked");
            const safe = m.replace(/"/g, "&quot;");
            return `<span class="${classes.join(" ")}" data-word="${safe}">${m}</span>`;
          });
          const span = doc.createElement("span");
          span.innerHTML = replaced;
          textNode.parentNode.replaceChild(span, textNode);
        });

        return doc.body.innerHTML;
      } catch (e) {
        try {
          const target = selectedKeyword || matchWord;
          const re = new RegExp(`\\b(${this.escapeRegExp(target)})\\b`, "gi");
          const replacementClass = selectedKeyword ? "matched-word matched-clicked" : "matched-word";
          return storyHtml.replace(re, `<span class="${replacementClass}">$1</span>`);
        } catch (_) {
          return storyHtml;
        }
      }
    },

    handleStoryContentClick(event, word) {
      const el = event.target;
      const storyWordEl = el.closest && el.closest(".story-word");
      const matched = storyWordEl || (el.closest && el.closest(".matched-word"));
      if (matched) {
        const kw = (matched.getAttribute && matched.getAttribute("data-word")) || (matched.textContent && matched.textContent.trim());
        if (kw) {
          this.onKeywordClick(kw, word);
        }
      }
    },

    onKeywordClick(keyword, word) {
      this.$set ? this.$set(word, "_selectedKeyword", keyword) : (word._selectedKeyword = keyword);
      this.searchQuery = keyword;
      this.performSearch();
    },

    escapeRegExp(string) {
      return string.replace(/[.*+?^${}()|[\\]\\\\]/g, "\\$&");
    },
  },
};
</script>

<style scoped>
.vocabulary-page {
  min-height: 100vh;
}

.hero-section {
  padding: 4rem 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.search-container {
  max-width: 600px;
  margin: 0 auto;
}

.direction-toggle {
  border-top-left-radius: 0.375rem;
  border-bottom-left-radius: 0.375rem;
  border-right: 1px solid rgba(0, 0, 0, 0.05);
  min-width: 78px;
}

.suggestions-dropdown {
  z-index: 1000;
  max-height: 300px;
  overflow-y: auto;
  border: 1px solid #dee2e6;
}

.suggestion-item {
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.suggestion-item:hover {
  background-color: #f8f9fa;
}

.suggestion-item:last-child {
  border-bottom: none;
}

.empty-state {
  color: #6c757d;
}

.loading-state {
  color: #6c757d;
}

/* Mobile Optimizations */
@media (max-width: 768px) {
  .hero-section {
    padding: 3rem 0;
  }

  .hero-section h1 {
    font-size: 2rem;
  }

  .hero-section p {
    font-size: 1rem;
  }

  .input-group-lg .form-control,
  .input-group-lg .btn {
    padding: 0.75rem 1rem;
    font-size: 1rem;
  }
}

@media (max-width: 576px) {
  .hero-section h1 {
    font-size: 1.75rem;
  }

  .display-4 {
    font-size: 1.75rem;
  }
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Focus styles for accessibility */
.form-control:focus {
  border-color: #86b7fe;
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
}
</style>
