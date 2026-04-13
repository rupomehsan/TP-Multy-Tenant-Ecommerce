<template>
  <div class="story-card" :class="{ active: isActive }">
    <!-- Story Header -->
    <div class="story-header">
      <div class="story-title-section">
        <div class="story-number-badge">
          <span class="story-number">{{ storyNumber }}</span>
        </div>
        <h3 class="story-title">
          {{ story.title || `Story ${storyNumber}` }}
        </h3>
      </div>
      <div class="story-meta">
        <div class="meta-item" v-if="story.story_group_id">
          <svg
            class="meta-icon"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          </svg>
          <span>Group {{ story.story_group_id }}</span>
        </div>
        <div class="meta-item" v-if="story.created_at">
          <svg
            class="meta-icon"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
          >
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12,6 12,12 16,14"></polyline>
          </svg>
          <span>{{ formatDate(story.created_at) }}</span>
        </div>
      </div>
    </div>
    <!-- Story Images (JSON array) at bottom -->
    <div
      class="story-gallery"
      v-if="story.story_image && story.story_image.length > 0"
    >
      <div class="section-header">
        <svg
          class="section-icon"
          width="18"
          height="18"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <circle cx="8.5" cy="8.5" r="1.5"></circle>
          <polyline points="21,15 16,10 5,21"></polyline>
        </svg>
        <h4>Gallery</h4>
        <span class="item-count"
          >{{ story.story_image.length }}
          {{ story.story_image.length === 1 ? "image" : "images" }}</span
        >
      </div>

      <div class="gallery-container">
        <div class="image-grid" v-if="story.story_image.length > 1">
          <div
            v-for="(img, index) in story.story_image"
            :key="index"
            class="gallery-item"
          >
            <a
              :href="getImageUrl(img)"
              data-fancybox="story-gallery"
              :data-caption="`${
                story.title || 'Story ' + storyNumber
              } - Gallery Image ${index + 1}`"
              class="gallery-link"
            >
              <img
                :src="getImageUrl(img)"
                :alt="
                  story.title || `Story ${storyNumber} - Image ${index + 1}`
                "
                class="gallery-image"
                @error="handleImageError"
              />
              <div class="image-overlay">
                <svg
                  class="zoom-icon"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <circle cx="11" cy="11" r="8"></circle>
                  <path d="m21 21-4.35-4.35"></path>
                  <line x1="11" y1="8" x2="11" y2="14"></line>
                  <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
              </div>
            </a>
          </div>
        </div>
        <div v-else class="single-gallery-image">
          <a
            :href="getImageUrl(story.story_image[0])"
            data-fancybox="story-gallery"
            :data-caption="`${
              story.title || 'Story ' + storyNumber
            } - Gallery Image`"
            class="gallery-link"
          >
            <img
              :src="getImageUrl(story.story_image[0])"
              :alt="story.title || `Story ${storyNumber}`"
              class="gallery-image single"
              @error="handleImageError"
            />
            <div class="image-overlay">
              <svg
                class="zoom-icon"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
                <line x1="11" y1="8" x2="11" y2="14"></line>
                <line x1="8" y1="11" x2="14" y2="11"></line>
              </svg>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Story Content -->
    <div class="story-content">
      <!-- Word visibility toggle controls - Only show in test mode -->
      <div class="story-controls" v-if="testMode && hasEnglishWords">
        <button
          @click="toggleAllWords"
          class="btn btn-sm"
          :class="
            allWordsVisible ? 'btn-outline-secondary' : 'btn-outline-primary'
          "
        >
          <i
            class="fas"
            :class="allWordsVisible ? 'fa-eye-slash' : 'fa-eye'"
          ></i>
          {{ allWordsVisible ? "Hide All Words" : "Show All Words" }}
        </button>
        <small class="text-muted ml-2">
          Click individual words to reveal them, or use the button above.
        </small>
      </div>

      <div>
        <div v-html="processedStoryContent"></div>
      </div>
    </div>

    <!-- Keywords Section -->
    <div class="story-keywords" v-if="keywordsList.length > 0">
      <div class="section-header">
        <svg
          class="section-icon"
          width="18"
          height="18"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"
          ></path>
          <line x1="7" y1="7" x2="7.01" y2="7"></line>
        </svg>
        <h4>Keywords</h4>
        <span class="item-count"
          >{{ keywordsList.length }}
          {{ keywordsList.length === 1 ? "tag" : "tags" }}</span
        >
      </div>
      <div class="keywords-list">
        <span
          v-for="(keyword, index) in keywordsList"
          :key="index"
          class="keyword-tag"
        >
          {{ keyword }}
        </span>
      </div>
    </div>

    <!-- Story Image (Single image - varchar) -->
    <div class="story-main-image" v-if="story.image">
      <div class="section-header">
        <svg
          class="section-icon"
          width="18"
          height="18"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <circle cx="8.5" cy="8.5" r="1.5"></circle>
          <polyline points="21,15 16,10 5,21"></polyline>
        </svg>
        <h4>Source Image</h4>
      </div>

      <div class="main-image-container">
        <a
          :href="getImageUrl(story.image)"
          data-fancybox="story-gallery"
          :data-caption="`${
            story.title || 'Story ' + storyNumber
          } - Source Image`"
          class="main-image-link"
        >
          <img
            :src="getImageUrl(story.image)"
            :alt="story.title || `Story ${storyNumber}`"
            class="main-story-image"
            @error="handleImageError"
          />
          <div class="image-overlay">
            <svg
              class="zoom-icon"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.35-4.35"></path>
              <line x1="11" y1="8" x2="11" y2="14"></line>
              <line x1="8" y1="11" x2="14" y2="11"></line>
            </svg>
          </div>
        </a>
      </div>
    </div>

    <!-- Story Actions -->
  </div>
</template>

<script>
import moment from "moment";
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";

export default {
  name: "StoryCard",
  props: {
    story: {
      type: Object,
      required: true,
    },
    storyNumber: {
      type: Number,
      required: true,
    },
    isActive: {
      type: Boolean,
      default: false,
    },
    testMode: {
      type: Boolean,
      default: false, // false = show all words (regular story view), true = hide words (test mode)
    },
  },
  data() {
    return {
      isExpanded: false,
      imageError: false,
      hiddenWords: new Set(), // Set to track which English words are hidden
      wordCounter: 0, // Counter to generate unique IDs for words
    };
  },
  computed: {
    isLongContent() {
      return this.story.story && this.story.story.length > 200;
    },
    keywordsList() {
      if (!this.story.keyword) return [];

      // If it's already an array, return as is
      if (Array.isArray(this.story.keyword)) {
        return this.story.keyword;
      }

      // If it's a string, split by comma and trim whitespace
      if (typeof this.story.keyword === "string") {
        return this.story.keyword
          .split(",")
          .map((keyword) => keyword.trim())
          .filter((keyword) => keyword.length > 0);
      }

      return [];
    },

    processedStoryContent() {
      if (!this.story.story) return "No story content available.";

      // If testMode is false (regular story view), return story as-is (all words visible)
      if (!this.testMode) {
        return this.story.story;
      }

      // If testMode is true, process the story content to hide English words
      return this.processStoryContent(this.story.story);
    },

    hasEnglishWords() {
      if (!this.story.story) return false;
      // Check if story contains English words (basic regex check)
      const englishWordRegex = /\b[A-Za-z]+\b/;
      return englishWordRegex.test(this.story.story);
    },

    allWordsVisible() {
      return this.hiddenWords.size === 0;
    },
  },
  methods: {
    formatDate(date) {
      return moment(date).format("MMM DD, YYYY");
    },

    getImageUrl(imagePath) {
      if (!imagePath) {
        console.warn("No image path provided");
        return "";
      }

      // If it's already a full URL, return as is
      if (imagePath.startsWith("http")) {
        console.log("Using full URL:", imagePath);
        return imagePath;
      }

      // Remove leading slash if present to avoid double slashes
      const cleanPath = imagePath.startsWith("/")
        ? imagePath.substring(1)
        : imagePath;

      // Construct the full URL with the base URL
      const fullUrl = `${window.location.origin}/${cleanPath}`;
      console.log("Generated image URL:", fullUrl);
      return fullUrl;
    },

    handleImageError(event) {
      // When image fails to load, set src to avatar.png
      event.target.src = "/avatar.png";
      // Also update the parent link href to avatar.png if it exists
      const parentLink = event.target.closest("a");
      if (parentLink) {
        parentLink.href = "/avatar.png";
      }
      this.imageError = true;
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },

    initFancybox() {
      // Initialize Fancybox for story images
      Fancybox.bind('[data-fancybox="story-gallery"]', {
        // Fancybox options for story gallery
        Toolbar: {
          display: {
            left: ["infobar"],
            middle: [
              "zoomIn",
              "zoomOut",
              "toggle1to1",
              "rotateCCW",
              "rotateCW",
              "flipX",
              "flipY",
            ],
            right: ["slideshow", "download", "close"],
          },
        },
        Thumbs: {
          autoStart: true,
        },
        // Better for gallery view
        wheel: "zoom",
        touch: {
          vertical: true,
          momentum: true,
        },
        // Animation settings
        showClass: "fancybox-fadeIn",
        hideClass: "fancybox-fadeOut",
        // Infinite loop for multiple images
        infinite: true,
      });
    },

    processStoryContent(htmlContent) {
      // Reset word counter for each processing
      this.wordCounter = 0;

      // Create a temporary div to parse HTML content
      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = htmlContent;

      // Process all text nodes to identify and wrap English words
      this.processTextNodes(tempDiv);

      return tempDiv.innerHTML;
    },

    processTextNodes(element) {
      // Process all child nodes
      const walker = document.createTreeWalker(
        element,
        NodeFilter.SHOW_TEXT,
        null,
        false
      );

      const textNodes = [];
      let node;
      while ((node = walker.nextNode())) {
        textNodes.push(node);
      }

      // Process each text node
      textNodes.forEach((textNode) => {
        const newContent = this.processTextContent(textNode.textContent);
        if (newContent !== textNode.textContent) {
          // Replace the text node with processed HTML
          const span = document.createElement("span");
          span.innerHTML = newContent;
          textNode.parentNode.replaceChild(span, textNode);
        }
      });
    },

    processTextContent(text) {
      // Regex to match English words (letters only, avoiding Bengali characters)
      const englishWordRegex = /\b[A-Za-z]+\b/g;

      return text.replace(englishWordRegex, (match) => {
        const wordId = `word-${this.wordCounter++}`;
        this.hiddenWords.add(wordId); // Initially hide all English words

        return `<span class="english-word hidden-word" 
                      data-word-id="${wordId}" 
                      data-original-word="${match}"
                      title="Click to reveal: ${match}">
                  ${"_".repeat(match.length)}
                </span>`;
      });
    },

    toggleWord(wordId) {
      if (this.hiddenWords.has(wordId)) {
        this.hiddenWords.delete(wordId);
      } else {
        this.hiddenWords.add(wordId);
      }

      // Find the word element and toggle its visibility
      const wordElement = document.querySelector(`[data-word-id="${wordId}"]`);
      if (wordElement) {
        const originalWord = wordElement.getAttribute("data-original-word");
        if (this.hiddenWords.has(wordId)) {
          // Hide the word
          wordElement.textContent = "_".repeat(originalWord.length);
          wordElement.classList.add("hidden-word");
          wordElement.classList.remove("revealed-word");
        } else {
          // Show the word
          wordElement.textContent = originalWord;
          wordElement.classList.remove("hidden-word");
          wordElement.classList.add("revealed-word");
        }
      }
    },

    toggleAllWords() {
      const wordElements = document.querySelectorAll(
        ".english-word[data-word-id]"
      );

      if (this.allWordsVisible) {
        // Hide all words
        wordElements.forEach((element) => {
          const wordId = element.getAttribute("data-word-id");
          const originalWord = element.getAttribute("data-original-word");
          this.hiddenWords.add(wordId);
          element.textContent = "_".repeat(originalWord.length);
          element.classList.add("hidden-word");
          element.classList.remove("revealed-word");
        });
      } else {
        // Show all words
        wordElements.forEach((element) => {
          const wordId = element.getAttribute("data-word-id");
          const originalWord = element.getAttribute("data-original-word");
          this.hiddenWords.delete(wordId);
          element.textContent = originalWord;
          element.classList.remove("hidden-word");
          element.classList.add("revealed-word");
        });
      }
    },

    setupWordClickHandlers() {
      // Remove any existing listeners first
      this.removeWordClickHandlers();

      // Add event delegation for word clicks
      this.handleWordClick = (event) => {
        if (event.target.classList.contains("english-word")) {
          const wordId = event.target.getAttribute("data-word-id");
          if (wordId) {
            this.toggleWord(wordId);
          }
        }
      };

      // Add the event listener to the story content container
      const storyContent = this.$el.querySelector(".story-content");
      if (storyContent) {
        storyContent.addEventListener("click", this.handleWordClick);
      }
    },

    removeWordClickHandlers() {
      if (this.handleWordClick) {
        const storyContent = this.$el?.querySelector(".story-content");
        if (storyContent) {
          storyContent.removeEventListener("click", this.handleWordClick);
        }
      }
    },

    handleKeydown(event) {
      // Fancybox handles ESC key automatically
    },
  },
  mounted() {
    // Add keyboard event listener for ESC key
    document.addEventListener("keydown", this.handleKeydown);
    // Initialize Fancybox
    this.initFancybox();
    // Add event delegation for word clicks
    this.setupWordClickHandlers();
  },

  updated() {
    // Re-initialize Fancybox when component updates
    this.initFancybox();
    // Re-setup word click handlers after content updates
    this.setupWordClickHandlers();
  },

  beforeUnmount() {
    // Clean up event listener and Fancybox instances
    document.removeEventListener("keydown", this.handleKeydown);
    this.removeWordClickHandlers();
    Fancybox.destroy();
  },
  emits: ["select-story", "view-details"],
};
</script>

<style lang="scss" scoped>
.story-card {
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 8px 24px rgba(0, 0, 0, 0.08);
  margin-bottom: 2rem;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(0, 0, 0, 0.05);
  position: relative;

  &::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  &:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1), 0 16px 40px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);

    &::before {
      opacity: 1;
    }
  }

  &.active {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15),
      0 16px 40px rgba(102, 126, 234, 0.2);

    &::before {
      opacity: 1;
    }
  }
}

.story-header {
  padding: 2rem 2rem 1.5rem;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);

  .story-title-section {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;

    .story-number-badge {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 12px;
      padding: 0.5rem;
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
      flex-shrink: 0;

      .story-number {
        color: white;
        font-size: 0.875rem;
        font-weight: 700;
        line-height: 1;
        display: block;
        min-width: 1.5rem;
        text-align: center;
      }
    }

    .story-title {
      margin: 0;
      font-size: 1.375rem;
      font-weight: 700;
      color: #1e293b;
      line-height: 1.3;
      letter-spacing: -0.025em;
      flex: 1;
    }
  }

  .story-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;

    .meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: white;
      padding: 0.5rem 0.75rem;
      border-radius: 8px;
      font-size: 0.875rem;
      color: #64748b;
      font-weight: 500;
      border: 1px solid rgba(0, 0, 0, 0.05);
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);

      .meta-icon {
        color: #94a3b8;
        flex-shrink: 0;
      }
    }
  }
}

.section-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);

  .section-icon {
    color: #667eea;
    flex-shrink: 0;
  }

  h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    flex: 1;
  }

  .item-count {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
  }
}

.story-content {
  padding: 2rem;

  .story-text {
    position: relative;
    margin-bottom: 1.5rem;

    .story-content-wrapper {
      font-size: 1rem;
      line-height: 1.7;
      color: #475569;
      max-height: 150px;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }

    &.expanded .story-content-wrapper {
      max-height: none;
      overflow: visible;
    }

    &:not(.expanded)::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3rem;
      background: linear-gradient(transparent, white);
      pointer-events: none;
    }

    &.expanded::after {
      display: none;
    }
  }

  .expand-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    color: #667eea;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem 0;
    transition: all 0.2s ease;

    &:hover {
      color: #4c51bf;
      transform: translateX(2px);
    }

    .expand-icon {
      transition: transform 0.3s ease;

      &.rotated {
        transform: rotate(180deg);
      }
    }
  }
}

.story-main-image {
  padding: 0 2rem 2rem;

  .main-image-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);

    .main-image-link {
      display: block;
      position: relative;
      overflow: hidden;

      &:hover .image-overlay {
        opacity: 1;
      }

      &:hover .main-story-image {
        transform: scale(1.05);
      }
    }

    .main-story-image {
      width: 100%;
      height: auto;
      min-height: 200px;
      max-height: 400px;
      object-fit: cover;
      display: block;
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .image-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;

      .zoom-icon {
        color: white;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
      }
    }
  }
}

.story-gallery {
  padding: 0 2rem 2rem;
  background: #fafbfc;
  border-top: 1px solid rgba(0, 0, 0, 0.06);

  .section-header {
    padding-top: 1.5rem;
    background: transparent;
  }

  .gallery-container {
    .image-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1rem;

      .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;

        &:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);

          .image-overlay {
            opacity: 1;
          }

          .gallery-image {
            transform: scale(1.1);
          }
        }

        .gallery-link {
          display: block;
          position: relative;
          overflow: hidden;
        }

        .gallery-image {
          width: 100%;
          height: 320px;
          object-fit: cover;
          transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-overlay {
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(0, 0, 0, 0.4);
          display: flex;
          align-items: center;
          justify-content: center;
          opacity: 0;
          transition: opacity 0.3s ease;

          .zoom-icon {
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
          }
        }
      }
    }

    .single-gallery-image {
      display: flex;
      justify-content: center;

      .gallery-link {
        display: block;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        background: white;

        &:hover .image-overlay {
          opacity: 1;
        }

        &:hover .gallery-image {
          transform: scale(1.05);
        }
      }

      .gallery-image.single {
        max-width: 500px;
        height: auto;
        max-height: 300px;
        object-fit: cover;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;

        .zoom-icon {
          color: white;
          filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
      }
    }
  }
}

.story-keywords {
  padding: 0 2rem 2rem;

  .keywords-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;

    .keyword-tag {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 600;
      box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
      transition: all 0.3s ease;

      &:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
      }
    }
  }
}

.story-actions {
  padding: 1.5rem 2rem;
  background: #f8fafc;
  display: flex;
  gap: 1rem;
  border-top: 1px solid rgba(0, 0, 0, 0.06);

  .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;

    &.btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);

      &:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
      }

      &.active {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
      }
    }

    &.btn-secondary {
      background: white;
      color: #667eea;
      border: 1px solid #667eea;

      &:hover {
        background: #667eea;
        color: white;
        transform: translateY(-1px);
      }
    }
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .story-card {
    margin-bottom: 1.5rem;
    border-radius: 12px;
  }

  .story-header {
    padding: 1.5rem 1.5rem 1rem;

    .story-title-section {
      .story-title {
        font-size: 1.25rem;
      }

      .story-number-badge {
        .story-number {
          font-size: 0.75rem;
        }
      }
    }

    .story-meta {
      flex-direction: column;
      gap: 0.5rem;

      .meta-item {
        align-self: flex-start;
      }
    }
  }

  .story-content {
    padding: 1.5rem;
  }

  .story-main-image,
  .story-keywords,
  .story-gallery {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }

  .story-gallery {
    .gallery-container {
      .image-grid {
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 0.75rem;

        .gallery-item .gallery-image {
          height: 100px;
        }
      }
    }
  }

  .story-actions {
    padding: 1.5rem;
    flex-direction: column;

    .btn {
      width: 100%;
      justify-content: center;
    }
  }
}

@media (max-width: 480px) {
  .story-header {
    padding: 1rem;

    .story-title-section {
      gap: 0.75rem;

      .story-title {
        font-size: 1.125rem;
      }
    }
  }

  .story-content {
    padding: 1rem;
  }

  .story-main-image,
  .story-keywords,
  .story-gallery {
    padding-left: 1rem;
    padding-right: 1rem;
  }

  .story-actions {
    padding: 1rem;
  }
}

// Story controls styling
.story-controls {
  padding: 1rem;
  background-color: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
  margin-bottom: 1rem;
  border-radius: 8px;

  .btn {
    margin-right: 0.5rem;

    &:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  }

  small {
    font-style: italic;
  }
}

// English word styling for story test
.story-content .english-word {
  cursor: pointer;
  border-radius: 4px;
  padding: 2px 4px;
  margin: 0 1px;
  transition: all 0.3s ease;
  display: inline-block;
  font-family: monospace;
  letter-spacing: 1px;

  &.hidden-word {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px dashed #dee2e6;

    &:hover {
      background-color: #e9ecef;
      border-color: #adb5bd;
      transform: scale(1.05);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  }

  &.revealed-word {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
    font-family: inherit;
    letter-spacing: normal;

    &:hover {
      background-color: #b6e3ea;
      border-color: #9fd3dd;
      transform: scale(1.02);
    }
  }
}

.story-content .english-word:active {
  transform: scale(0.98);
}

// Add a subtle animation when words are revealed
@keyframes wordReveal {
  0% {
    opacity: 0;
    transform: scale(0.8);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.story-content .english-word.revealed-word {
  animation: wordReveal 0.3s ease-out;
}
</style>
