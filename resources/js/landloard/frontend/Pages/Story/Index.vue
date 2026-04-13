<template>
  <!-- Header -->
  <div class="stories-header hero-section bg-primary py-5">
    <h1 class="page-title text-white">📚 Vocabulary Stories</h1>
    <p class="page-description text-white my-3">
      Explore vocabulary stories to enhance your learning experience
    </p>
    <Link
      href="/story-test"
      class="mx-auto text-center"
      style="text-decoration: none"
    >
      <button data-v-d691716a="" class="language-toggle-btn active my-3">
        <div data-v-d691716a="" class="language-display">
          <span data-v-d691716a="" class="from-lang"
            ><i data-v-d691716a="" class="fas fa-flag-usa"></i> Participate in a
            Story Evaluation</span
          >
        </div>
      </button>
    </Link>
  </div>

  <div class="container">
    <!-- Loading State -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Loading stories...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="error-container">
      <div class="error-icon">⚠️</div>
      <h3>Error Loading Stories</h3>
      <p>{{ error }}</p>
      <button @click="fetchStories" class="retry-btn">Try Again</button>
    </div>

    <!-- Stories Content -->
    <div v-else-if="totalStories > 0" class="stories-content">
      <!-- Stats and Controls -->
      <div class="stories-controls">
        <div class="stories-stats">
          <div class="stat-item">
            <span class="stat-number">{{ totalStories }}</span>
            <span class="stat-label">Total Stories</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">{{ currentStoryNumber }}</span>
            <span class="stat-label">Current Story</span>
          </div>
        </div>

        <!-- Range Selector -->
        <div class="range-selector">
          <label for="range-select">Select Range:</label>
          <select
            id="range-select"
            v-model="selectedRange"
            @change="onRangeChange"
            class="range-dropdown"
          >
            <option v-for="range in ranges" :key="range.start" :value="range">
              {{ range.label }} ({{
                Math.min(range.end, totalStories) - range.start + 1
              }}
              stories)
            </option>
          </select>
        </div>

        <!-- Navigation Controls -->
        <div class="navigation-controls">
          <button
            @click="previousStory"
            :disabled="currentStoryIndex <= 0"
            class="nav-btn prev-btn"
          >
            ← Previous
          </button>

          <span class="story-counter">
            {{ currentStoryIndex + 1 }} of {{ currentRangeStories.length }}
          </span>

          <button
            @click="nextStory"
            :disabled="
              currentStoryIndex >= currentRangeStories.length - 1 &&
              selectedRange.end >= totalStories
            "
            class="nav-btn next-btn"
          >
            Next →
          </button>
        </div>
      </div>

      <!-- Current Story Display -->
      <div class="current-story-section" v-if="currentStory">
        <div class="story-navigation-info">
          <div class="range-info">
            Current Range: <strong>{{ selectedRange.label }}</strong>
          </div>
          <div class="progress-bar">
            <div
              class="progress-fill"
              :style="{ width: progressPercentage + '%' }"
            ></div>
          </div>
        </div>

        <StoryCard
          :story="currentStory"
          :story-number="currentStoryNumber"
          :is-active="true"
          :test-mode="false"
          @select-story="onSelectStory"
          @view-details="onViewDetails"
        />
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-state">
      <div class="empty-icon">📖</div>
      <h3>No Stories Available</h3>
      <p>There are currently no vocabulary stories to display.</p>
      <button @click="fetchStories" class="retry-btn">Refresh</button>
    </div>
  </div>
</template>

<script>
import { StoriesService } from "./Services/StoriesService.js";
import StoryCard from "./Components/StoryCard.vue";

export default {
  name: "StoriesIndex",
  components: {
    StoryCard,
  },
  data() {
    return {
      // Data state
      stories: [],
      totalStories: 0,
      ranges: [],
      selectedRange: null,
      currentRangeStories: [],
      currentStoryIndex: 0,

      // UI state
      loading: false,
      error: null,
      showAllInRange: false,

      // Configuration
      rangeSize: 20,
    };
  },
  computed: {
    currentStory() {
      return this.currentRangeStories[this.currentStoryIndex] || null;
    },

    currentStoryNumber() {
      if (!this.selectedRange) return 0;
      return this.selectedRange.start + this.currentStoryIndex;
    },

    progressPercentage() {
      if (!this.currentRangeStories.length) return 0;
      return (
        ((this.currentStoryIndex + 1) / this.currentRangeStories.length) * 100
      );
    },
  },
  async mounted() {
    await this.initializeStories();
  },
  methods: {
    async initializeStories() {
      this.loading = true;
      this.error = null;

      try {
        // First, get total count of stories
        const countResponse = await StoriesService.fetchStories({
          limit: 1,
          page: 1,
        });

        if (countResponse.success) {
          this.totalStories = countResponse.totalStories;
          this.generateRanges();

          // Load first range
          if (this.ranges.length > 0) {
            this.selectedRange = this.ranges[0];
            await this.loadRangeStories();
          }
        } else {
          this.error = countResponse.error;
        }
      } catch (err) {
        this.error = "Failed to initialize stories";
        console.error("Initialization error:", err);
      } finally {
        this.loading = false;
      }
    },

    generateRanges() {
      this.ranges = StoriesService.calculateRanges(
        this.totalStories,
        this.rangeSize
      );
    },

    async loadRangeStories() {
      if (!this.selectedRange) return;

      this.loading = true;

      try {
        const response = await StoriesService.fetchStories({
          limit: this.rangeSize,
          page: this.selectedRange.page,
        });

        if (response.success) {
          this.currentRangeStories = response.data;
          this.currentStoryIndex = 0; // Reset to first story in range
        } else {
          this.error = response.error;
          this.currentRangeStories = [];
        }
      } catch (err) {
        this.error = "Failed to load stories for selected range";
        this.currentRangeStories = [];
        console.error("Range loading error:", err);
      } finally {
        this.loading = false;
      }
    },

    async onRangeChange() {
      await this.loadRangeStories();
      this.showAllInRange = false; // Hide grid when changing range
    },

    async nextStory() {
      if (this.currentStoryIndex < this.currentRangeStories.length - 1) {
        // Move to next story in current range
        this.currentStoryIndex++;
      } else if (this.selectedRange.end < this.totalStories) {
        // Move to next range
        const currentRangeIndex = this.ranges.findIndex(
          (r) => r.start === this.selectedRange.start
        );
        if (currentRangeIndex < this.ranges.length - 1) {
          this.selectedRange = this.ranges[currentRangeIndex + 1];
          await this.loadRangeStories();
        }
      }
    },

    async previousStory() {
      if (this.currentStoryIndex > 0) {
        // Move to previous story in current range
        this.currentStoryIndex--;
      } else if (this.selectedRange.start > 1) {
        // Move to previous range
        const currentRangeIndex = this.ranges.findIndex(
          (r) => r.start === this.selectedRange.start
        );
        if (currentRangeIndex > 0) {
          this.selectedRange = this.ranges[currentRangeIndex - 1];
          await this.loadRangeStories();
          // Go to last story of previous range
          this.currentStoryIndex = this.currentRangeStories.length - 1;
        }
      }
    },

    async fetchStories() {
      await this.initializeStories();
    },

    onSelectStory(story) {
      console.log("Selected story:", story);
      // You can emit an event or handle story selection here
      this.$emit("story-selected", story);
    },

    onViewDetails(story) {
      console.log("View details for story:", story);
      // Navigate to story details page or open modal
      this.$emit("view-story-details", story);
    },
  },
};
</script>

<style lang="scss" scoped>
.stories-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Header */
.stories-header {
  text-align: center;
  margin-bottom: 3rem;

  .page-title {
    font-size: 3.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 1rem;

    @media (max-width: 768px) {
      font-size: 2rem;
    }
  }

  .page-description {
    font-size: 1.125rem;
    color: #6c757d;
    margin: 0;
    max-width: 600px;
    margin: 0 auto;
  }
}

/* Loading State */
.loading-container {
  text-align: center;
  padding: 4rem 2rem;

  .spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
  }

  p {
    color: #6c757d;
    font-size: 1.125rem;
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Error State */
.error-container {
  text-align: center;
  padding: 4rem 2rem;
  background: #fff5f5;
  border-radius: 12px;
  border: 1px solid #fed7d7;

  .error-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
  }

  h3 {
    color: #e53e3e;
    margin: 0 0 1rem;
  }

  p {
    color: #742a2a;
    margin: 0 0 2rem;
  }

  .retry-btn {
    background: #e53e3e;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s ease;

    &:hover {
      background: #c53030;
    }
  }
}

/* Stories Controls */
.stories-controls {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 2rem;
  align-items: center;

  @media (max-width: 768px) {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
}

.stories-stats {
  display: flex;
  gap: 2rem;

  @media (max-width: 768px) {
    justify-content: center;
  }

  .stat-item {
    text-align: center;

    .stat-number {
      display: block;
      font-size: 2rem;
      font-weight: 700;
      color: #007bff;
      line-height: 1;
    }

    .stat-label {
      display: block;
      font-size: 0.875rem;
      color: #6c757d;
      margin-top: 0.25rem;
    }
  }
}

.range-selector {
  text-align: center;

  label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
  }

  .range-dropdown {
    padding: 0.75rem 1rem;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-size: 1rem;
    background: white;
    cursor: pointer;
    transition: border-color 0.3s ease;
    min-width: 200px;

    &:focus {
      border-color: #007bff;
      outline: none;
    }
  }
}

.navigation-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: flex-end;

  @media (max-width: 768px) {
    justify-content: center;
  }

  .nav-btn {
    padding: 0.75rem 1.25rem;
    border: 2px solid #007bff;
    border-radius: 8px;
    background: white;
    color: #007bff;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;

    &:hover:not(:disabled) {
      background: #007bff;
      color: white;
    }

    &:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      border-color: #dee2e6;
      color: #6c757d;
    }
  }

  .story-counter {
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
  }
}

/* Current Story Section */
.current-story-section {
  margin-bottom: 2rem;

  .story-navigation-info {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;

    @media (max-width: 768px) {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }

    .range-info {
      font-size: 0.875rem;
      color: #495057;

      strong {
        color: #007bff;
      }
    }

    .progress-bar {
      width: 200px;
      height: 8px;
      background: #dee2e6;
      border-radius: 4px;
      overflow: hidden;

      @media (max-width: 768px) {
        width: 100%;
        max-width: 300px;
      }

      .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #007bff, #0056b3);
        transition: width 0.3s ease;
      }
    }
  }
}

/* Range Stories Toggle */
.range-stories-toggle {
  text-align: center;
  margin-bottom: 2rem;

  .toggle-btn {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s ease;

    &:hover {
      background: #5a6268;
    }
  }
}

/* Stories Grid */
.stories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;

  @media (max-width: 768px) {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: #f8f9fa;
  border-radius: 12px;

  .empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.7;
  }

  h3 {
    color: #495057;
    margin: 0 0 1rem;
  }

  p {
    color: #6c757d;
    margin: 0 0 2rem;
    font-size: 1.125rem;
  }

  .retry-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.3s ease;

    &:hover {
      background: #0056b3;
    }
  }
}

/* Responsive Adjustments */
@media (max-width: 576px) {
  .stories-container {
    padding: 1rem 0.5rem;
  }

  .stories-controls {
    padding: 1.5rem 1rem;
  }

  .stories-stats {
    gap: 1rem;

    .stat-item .stat-number {
      font-size: 1.5rem;
    }
  }

  .navigation-controls {
    flex-wrap: wrap;
    gap: 0.5rem;

    .nav-btn {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }
  }
}

/* Focus and Accessibility */
*:focus {
  outline: 2px solid #007bff;
  outline-offset: 2px;
}

button:focus,
select:focus {
  outline: 2px solid #007bff;
  outline-offset: 2px;
}

/* Animation for story transitions */
.current-story-section {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
