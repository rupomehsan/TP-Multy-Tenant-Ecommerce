<template>
  <div>
    <!-- Loading State - Show while checking authentication -->
    <div v-if="isLoading" class="loading-container">
      <div class="loading-content">
        <div class="spinner"></div>
        <h3>Loading Profile...</h3>
        <p>Please wait while we verify your authentication.</p>
      </div>
    </div>

    <!-- Profile Dashboard - Show when authenticated -->
    <div v-else-if="is_auth && !isLoading" class="profile-dashboard">
      <!-- Header Section -->
      <div class="dashboard-header">
        <div class="container">
          <div class="header-content">
            <div class="user-info">
              <div class="user-avatar">
                <img :src="auth_info.avatar || auth_info.image || '/avatar.png'" :alt="auth_info.name || 'User'" />
              </div>
              <div class="user-details">
                <h1 class="user-name text-capitalize">{{ auth_info.name || user.name || "User" }}</h1>
                <p class="user-email">{{ auth_info.email || user.email || "user@example.com" }}</p>
                <div class="user-stats">
                  <span class="stat-item">
                    <i class="fas fa-brain"></i>
                    {{ userStats.totalRevision }} Revision
                  </span>
                  <span class="stat-item">
                    <i class="fas fa-book"></i>
                    {{ userStats.totalStories }} Stories
                  </span>
                  <span class="stat-item">
                    <i class="fas fa-trophy"></i>
                    {{ userStats.testsCompleted }} Tests
                  </span>
                </div>
              </div>
            </div>
            <div class="header-actions">
              <button @click="logout" class="btn btn-outline-primary">
                <i class="fas fa-sign-out-alt"></i>
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="dashboard-nav">
        <div class="container">
          <nav class="nav-tabs">
            <button v-for="tab in tabs" :key="tab.id" @click="switchTab(tab.id)" :class="['nav-tab', { active: activeTab === tab.id }]">
              <i :class="tab.icon"></i>
              {{ tab.label }}
              <span v-if="tab.count !== undefined" class="tab-count">{{ tab.count }}</span>
            </button>
          </nav>
        </div>
      </div>

      <!-- Dashboard Content -->
      <div class="dashboard-content">
        <div class="container">
          <!-- Overview Tab -->
          <div v-if="activeTab === 'overview'" class="tab-content">
            <div class="overview-grid">
              <!-- Learning Progress -->
              <div class="card learning-progress">
                <div class="card-header">
                  <h3><i class="fas fa-chart-line"></i> Learning Progress</h3>
                </div>
                <div class="card-body">
                  <div class="progress-stats">
                    <div class="progress-item">
                      <div class="progress-label">Words Learned</div>
                      <div class="progress-bar">
                        <div class="progress-fill" :style="{ width: userStats.learningProgress + '%' }"></div>
                      </div>
                      <div class="progress-value">{{ userStats.learningProgress }}%</div>
                    </div>
                    <div class="progress-item">
                      <div class="progress-label">Weekly Goal</div>
                      <div class="progress-bar">
                        <div class="progress-fill weekly" :style="{ width: userStats.weeklyProgress + '%' }"></div>
                      </div>
                      <div class="progress-value">{{ userStats.weeklyProgress }}%</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Quick Stats -->
              <div class="card quick-stats">
                <div class="card-header">
                  <h3><i class="fas fa-tachometer-alt"></i> Quick Stats</h3>
                </div>
                <div class="card-body">
                  <div class="stats-grid">
                    <div class="stat-card">
                      <div class="stat-icon revision">
                        <i class="fas fa-brain"></i>
                      </div>
                      <div class="stat-info">
                        <div class="stat-number">{{ userStats.totalRevision }}</div>
                        <div class="stat-label">Revision Words</div>
                      </div>
                    </div>
                    <div class="stat-card">
                      <div class="stat-icon stories">
                        <i class="fas fa-book-open"></i>
                      </div>
                      <div class="stat-info">
                        <div class="stat-number">{{ userStats.totalStories }}</div>
                        <div class="stat-label">Stories Read</div>
                      </div>
                    </div>
                    <div class="stat-card">
                      <div class="stat-icon tests">
                        <i class="fas fa-brain"></i>
                      </div>
                      <div class="stat-info">
                        <div class="stat-number">{{ userStats.testsCompleted }}</div>
                        <div class="stat-label">Tests Taken</div>
                      </div>
                    </div>
                    <div class="stat-card">
                      <div class="stat-icon streak">
                        <i class="fas fa-fire"></i>
                      </div>
                      <div class="stat-info">
                        <div class="stat-number">{{ userStats.currentStreak }}</div>
                        <div class="stat-label">Day Streak</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Recent Activity -->
              <div class="card recent-activity">
                <div class="card-header">
                  <h3><i class="fas fa-clock"></i> Recent Activity</h3>
                </div>
                <div class="card-body">
                  <div class="activity-list">
                    <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
                      <div class="activity-icon" :class="activity.type">
                        <i :class="activity.icon"></i>
                      </div>
                      <div class="activity-content">
                        <div class="activity-text">{{ activity.text }}</div>
                        <div class="activity-time">{{ formatTimeAgo(activity.timestamp) }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Revision Tab -->
          <div v-if="activeTab === 'revision'" class="tab-content">
            <div class="section-header">
              <h2><i class="fas fa-brain"></i> My Revision Words</h2>
              <div class="section-actions">
                <div class="search-box">
                  <i class="fas fa-search"></i>
                  <input v-model="revisionSearch" type="text" placeholder="Search revision..." class="search-input" />
                </div>
                <select v-model="revisionSort" class="sort-select">
                  <option value="recent">Recently Added</option>
                  <option value="alphabetical">Alphabetical</option>
                  <option value="difficulty">Difficulty</option>
                </select>
              </div>
            </div>

            <div v-if="filteredRevision.length > 0" class="">
              <div v-for="word in filteredRevision" :key="word.id" class="vocabulary-card">
                <div class="card word-card shadow-sm h-100 my-2">
                  <div class="card-header d-flex justify-content-between align-items-start">
                    <div class="word-header flex-grow-1">
                      <div class="word-title-container mb-2">
                        <h4 class="word-title text-capitalize">
                          <span class="word-text">{{ word.word || word.english || 'N/A' }}</span>
                          <span v-if="word.part_of_speech || word.word_type" class="badge bg-primary ms-2">
                            {{ word.part_of_speech || word.word_type }}
                          </span>
                        </h4>
                      </div>
                      <div class="word-meta">
                        <p class="meaning mb-1">{{ word.meaning || word.bangla || word.definition || 'No meaning available' }}</p>
                        <small v-if="word.pronunciation" class="text-muted">
                          <i class="fas fa-volume-up me-1"></i>
                          {{ word.pronunciation }}
                        </small>
                      </div>
                    </div>
                    <div class="word-actions">
                      <button 
                        @click="removeRevision(word.id)"
                        class="btn btn-sm btn-warning"
                        title="Remove from revision"
                      >
                        <i class="fas fa-heart"></i>
                      </button>
                      <button 
                        @click="toggleWordDetails(word.id)"
                        class="btn btn-sm btn-outline-primary"
                        :title="word._expanded ? 'Hide details' : 'Show details'"
                      >
                        <i class="fas" :class="word._expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                      </button>
                    </div>
                  </div>
                  
                  <!-- Expanded Details -->
                  <div v-if="word._expanded" class="card-body">
                    <!-- Loading State -->
                    <div v-if="word._loadingDetails" class="text-center py-3">
                      <div class="spinner-border spinner-border-sm text-primary"></div>
                      <small class="text-muted ms-2">Loading details...</small>
                    </div>
                    
                    <!-- Word Details -->
                    <div v-else>
                      <!-- Synonyms -->
                      <div v-if="getSynonyms(word).length > 0" class="mb-3">
                        <h6 class="fw-bold text-success">
                          <i class="fas fa-plus-circle me-2"></i>
                          Synonyms
                        </h6>
                        <div class="tags-container">
                          <span 
                            v-for="synonym in getSynonyms(word)" 
                            :key="synonym"
                            @click="searchKeyword(synonym)"
                            class="badge bg-success-subtle text-success synonym-tag me-1 mb-1"
                          >
                            {{ synonym }}
                          </span>
                        </div>
                      </div>
                      
                      <!-- Antonyms -->
                      <div v-if="getAntonyms(word).length > 0" class="mb-3">
                        <h6 class="fw-bold text-danger">
                          <i class="fas fa-minus-circle me-2"></i>
                          Antonyms
                        </h6>
                        <div class="tags-container">
                          <span 
                            v-for="antonym in getAntonyms(word)" 
                            :key="antonym"
                            @click="searchKeyword(antonym)"
                            class="badge bg-danger-subtle text-danger antonym-tag me-1 mb-1"
                          >
                            {{ antonym }}
                          </span>
                        </div>
                      </div>
                      
                      <!-- Example Sentences -->
                      <div v-if="getExampleSentences(word).length > 0" class="mb-3">
                        <h6 class="fw-bold text-info">
                          <i class="fas fa-quote-left me-2"></i>
                          Example Sentences
                        </h6>
                        <div v-for="(example, index) in getExampleSentences(word)" :key="index" class="example-sentence p-3 mb-2 rounded">
                          <blockquote class="mb-1">"{{ example.sentence || example.english || example }}"</blockquote>
                          <small v-if="example.meaning || example.bangla || example.translation" class="text-muted">
                            ({{ example.meaning || example.bangla || example.translation }})
                          </small>
                        </div>
                      </div>
                      
                      <!-- Story Content -->
                      <div v-if="word.stories?.length || word.story || word.story_html" class="mb-3">
                        <h6 class="fw-bold text-primary">
                          <i class="fas fa-book me-2"></i>
                          Story
                        </h6>

                        <!-- Stories array from API (prioritized) -->
                        <div v-if="word.stories && word.stories.length > 0">
                          <div 
                            v-for="(storyItem, index) in word.stories" 
                            :key="`story-${index}`"
                            class="story-block mb-3 p-3 bg-light rounded border"
                            v-html="highlightWordsInStory(storyItem.story || storyItem.content || storyItem, word.word || word.english)"
                          ></div>
                        </div>

                        <!-- Fallback: single story field (only if no stories array) -->
                        <div v-else-if="word.story || word.story_html" class="story-block mb-3 p-3 bg-light rounded border">
                          <div 
                            v-html="highlightWordsInStory(word.story || word.story_html, word.word || word.english)"
                            class="story-content"
                          ></div>
                        </div>
                      </div>

                      <!-- Keywords -->
                      <div v-if="getKeywords(word).length > 0" class="mb-3">
                        <h6 class="fw-bold text-secondary">
                          <i class="fas fa-tags me-2"></i>
                          Keywords
                        </h6>
                        <div class="tags-container">
                          <span 
                            v-for="keyword in getKeywords(word)" 
                            :key="keyword"
                            @click="searchKeyword(keyword)"
                            class="badge bg-secondary keyword-badge me-1 mb-1"
                          >
                            {{ keyword }}
                          </span>
                        </div>
                      </div>
                      
                      <!-- Additional Information -->
                      <div v-if="word.etymology || word.usage_notes" class="border-top pt-3">
                        <div v-if="word.etymology" class="mb-2">
                          <strong class="text-secondary">Etymology:</strong>
                          <span class="ms-2">{{ word.etymology }}</span>
                        </div>
                        <div v-if="word.usage_notes" class="mb-2">
                          <strong class="text-secondary">Usage Notes:</strong>
                          <span class="ms-2">{{ word.usage_notes }}</span>
                        </div>
                      </div>
                      
                      <!-- Metadata -->
                      <div v-if="word.created_at || word.difficulty_level" class="border-top pt-2 mt-3">
                        <div class="row text-muted small">
                          <div class="col-6" v-if="word.difficulty_level">
                            <i class="fas fa-signal me-1"></i>
                            Level: {{ word.difficulty_level }}
                          </div>
                          <div class="col-6" v-if="word.created_at">
                            <i class="fas fa-calendar me-1"></i>
                            Added: {{ formatDate(word.created_at) }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="empty-state">
              <i class="fas fa-brain fa-3x"></i>
              <h3>No Revision Words Yet</h3>
              <p>Start adding words to your revision list to see them here.</p>
              <router-link to="/words" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Explore Words
              </router-link>
            </div>
          </div>

          <!-- Stories Tab -->
          <div v-if="activeTab === 'stories'" class="tab-content">
            <div class="section-header">
              <h2><i class="fas fa-book-open"></i> My Story Collection</h2>
              <div class="section-actions">
                <div class="search-box">
                  <i class="fas fa-search"></i>
                  <input v-model="storiesSearch" type="text" placeholder="Search stories..." class="search-input" />
                </div>
                <select v-model="storiesFilter" class="sort-select">
                  <option value="all">All Stories</option>
                  <option value="reading">Currently Reading</option>
                  <option value="completed">Completed</option>
                  <option value="bookmarked">Bookmarked</option>
                </select>
              </div>
            </div>

            <div v-if="filteredStories.length > 0" class="stories-grid">
              <div v-for="story in filteredStories" :key="story.id" class="story-card">
                <div class="story-image">
                  <img :src="story.image || '/dummy.png'" :alt="story.title" />
                  <div class="story-progress">
                    <div class="progress-circle" :class="{ completed: story.isCompleted }">
                      <span>{{ story.readingProgress }}%</span>
                    </div>
                  </div>
                </div>
                <div class="story-content">
                  <h3 class="story-title">{{ story.title }}</h3>
                  <p class="story-excerpt">{{ story.excerpt }}</p>
                  <div class="story-meta">
                    <span class="story-words">
                      <i class="fas fa-spell-check"></i>
                      {{ story.wordCount }} words
                    </span>
                    <span class="story-difficulty" :class="story.difficulty">
                      {{ story.difficulty }}
                    </span>
                  </div>
                </div>
                <div class="story-actions">
                  <button @click="readStory(story)" class="btn btn-primary">
                    <i class="fas" :class="story.isCompleted ? 'fa-redo' : 'fa-play'"></i>
                    {{ story.isCompleted ? "Re-read" : "Continue Reading" }}
                  </button>
                  <button @click="removeStory(story.id)" class="btn btn-outline-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
            </div>

            <div v-else class="empty-state">
              <i class="fas fa-book fa-3x"></i>
              <h3>No Stories in Your Collection</h3>
              <p>Add stories to your reading list to track your progress.</p>
              <router-link to="/stories" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Browse Stories
              </router-link>
            </div>
          </div>

          <!-- Tests Tab -->
          <div v-if="activeTab === 'tests'" class="tab-content">
            <div class="section-header">
              <h2><i class="fas fa-brain"></i> Vocabulary Tests</h2>
              <button @click="showCreateTest = true" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create New Test
              </button>
            </div>

            <!-- Test Types -->
            <div class="test-types">
              <div class="test-type-card" @click="startQuickTest('flashcard')">
                <div class="test-icon">
                  <i class="fas fa-id-card"></i>
                </div>
                <h3>Flashcard Test</h3>
                <p>Quick flashcard review of your revision words</p>
                <div class="test-meta">
                  <span><i class="fas fa-clock"></i> 5-10 min</span>
                  <span><i class="fas fa-star"></i> Easy</span>
                </div>
              </div>

              <div class="test-type-card" @click="startQuickTest('multiple-choice')">
                <div class="test-icon">
                  <i class="fas fa-list-alt"></i>
                </div>
                <h3>Multiple Choice</h3>
                <p>Choose the correct definition from multiple options</p>
                <div class="test-meta">
                  <span><i class="fas fa-clock"></i> 10-15 min</span>
                  <span><i class="fas fa-star"></i> Medium</span>
                </div>
              </div>

              <div class="test-type-card" @click="startQuickTest('spelling')">
                <div class="test-icon">
                  <i class="fas fa-spell-check"></i>
                </div>
                <h3>Spelling Test</h3>
                <p>Test your spelling skills with audio pronunciation</p>
                <div class="test-meta">
                  <span><i class="fas fa-clock"></i> 15-20 min</span>
                  <span><i class="fas fa-star"></i> Hard</span>
                </div>
              </div>

              <div class="test-type-card" @click="startQuickTest('sentence')">
                <div class="test-icon">
                  <i class="fas fa-quote-right"></i>
                </div>
                <h3>Sentence Completion</h3>
                <p>Complete sentences using the correct vocabulary</p>
                <div class="test-meta">
                  <span><i class="fas fa-clock"></i> 20-25 min</span>
                  <span><i class="fas fa-star"></i> Expert</span>
                </div>
              </div>
            </div>

            <!-- Test History -->
            <div class="test-history">
              <h3><i class="fas fa-history"></i> Recent Test Results</h3>
              <div v-if="testHistory.length > 0" class="test-history-list">
                <div v-for="test in testHistory" :key="test.id" class="test-history-item">
                  <div class="test-info">
                    <div class="test-name">{{ test.name }}</div>
                    <div class="test-date">{{ formatDate(test.completedAt) }}</div>
                  </div>
                  <div class="test-score" :class="getScoreClass(test.score)">{{ test.score }}%</div>
                  <div class="test-actions">
                    <button @click="viewTestDetails(test)" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button @click="retakeTest(test)" class="btn btn-sm btn-primary">
                      <i class="fas fa-redo"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div v-else class="empty-state">
                <i class="fas fa-clipboard-list fa-2x"></i>
                <p>No test history yet. Take your first test above!</p>
              </div>
            </div>
          </div>

          <!-- Settings Tab -->
          <div v-if="activeTab === 'settings'" class="tab-content">
            <div class="settings-content">
              <div class="settings-section">
                <h3><i class="fas fa-user"></i> Profile Settings</h3>
                <div class="setting-item">
                  <label>Display Name</label>
                  <input v-model="userSettings.displayName" type="text" class="setting-input" />
                </div>
                <div class="setting-item">
                  <label>Learning Goal (words per week)</label>
                  <input v-model="userSettings.weeklyGoal" type="number" class="setting-input" />
                </div>
              </div>

              <div class="settings-section">
                <h3><i class="fas fa-bell"></i> Notifications</h3>
                <div class="setting-item">
                  <label class="checkbox-label">
                    <input v-model="userSettings.dailyReminder" type="checkbox" />
                    <span class="checkmark"></span>
                    Daily learning reminder
                  </label>
                </div>
                <div class="setting-item">
                  <label class="checkbox-label">
                    <input v-model="userSettings.weeklyProgress" type="checkbox" />
                    <span class="checkmark"></span>
                    Weekly progress summary
                  </label>
                </div>
              </div>

              <div class="settings-section">
                <h3><i class="fas fa-palette"></i> Appearance</h3>
                <div class="setting-item">
                  <label>Theme</label>
                  <select v-model="userSettings.theme" class="setting-select">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="auto">Auto</option>
                  </select>
                </div>
              </div>

              <div class="settings-actions">
                <button @click="saveSettings" class="btn btn-primary">
                  <i class="fas fa-save"></i>
                  Save Changes
                </button>
                <button @click="resetSettings" class="btn btn-outline-secondary">
                  <i class="fas fa-undo"></i>
                  Reset to Default
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modals -->
      <div v-if="showCreateTest" class="modal-overlay" @click="showCreateTest = false">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>Create Custom Test</h3>
            <button @click="showCreateTest = false" class="modal-close">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Test Name</label>
              <input v-model="newTest.name" type="text" class="form-input" placeholder="Enter test name" />
            </div>
            <div class="form-group">
              <label>Test Type</label>
              <select v-model="newTest.type" class="form-input">
                <option value="flashcard">Flashcard</option>
                <option value="multiple-choice">Multiple Choice</option>
                <option value="spelling">Spelling</option>
                <option value="sentence">Sentence Completion</option>
              </select>
            </div>
            <div class="form-group">
              <label>Number of Questions</label>
              <input v-model="newTest.questionCount" type="number" min="5" max="50" class="form-input" />
            </div>
            <div class="form-group">
              <label>Word Source</label>
              <select v-model="newTest.wordSource" class="form-input">
                <option value="revision">My Revision</option>
                <option value="recent">Recently Viewed</option>
                <option value="random">Random Words</option>
                <option value="difficulty">By Difficulty</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button @click="showCreateTest = false" class="btn btn-outline-secondary">Cancel</button>
            <button @click="createCustomTest" class="btn btn-primary">Create Test</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End Profile Dashboard -->

    <!-- Not Authenticated State - Show when user is definitely not authenticated -->
    <div v-else-if="!is_auth && !isLoading" class="auth-required">
      <div class="auth-content">
        <i class="fas fa-lock fa-3x"></i>
        <h3>Authentication Required</h3>
        <p>Please log in to access your profile dashboard.</p>
        <Link href="/login" class="btn btn-primary d-flex align-items-center"> Go to Login </Link>
      </div>
    </div>

    <!-- Debug State - Temporary for troubleshooting -->
    <div v-else class="debug-state">
      <div class="debug-content">
        <h3>Debug Information</h3>
        <p><strong>isLoading:</strong> {{ isLoading }}</p>
        <p><strong>is_auth:</strong> {{ is_auth }}</p>
        <p><strong>auth_info:</strong> {{ JSON.stringify(auth_info) }}</p>
        <p><strong>Token exists:</strong> {{ !!localStorage.getItem("auth_token") }}</p>
        <button @click="retryAuth" class="btn btn-primary">Retry Authentication</button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { auth_store } from "../../GlobalStore/auth_store";
import axios from "axios";

export default {
  name: "ProfileDashboard",
  data() {
    return {
      isLoading: true,
      activeTab: "overview",
      showEditProfile: false,
      showCreateTest: false,

      // User data
      user: {
        name: "John Doe",
        email: "john@example.com",
        avatar: "/avatar.png",
      },

      // User statistics
      userStats: {
        totalRevision: 0,
        totalStories: 0,
        testsCompleted: 0,
        currentStreak: 0,
        learningProgress: 65,
        weeklyProgress: 80,
      },

      // Tabs configuration
      tabs: [
        { id: "overview", label: "Overview", icon: "fas fa-tachometer-alt" },
        { id: "revision", label: "Revision", icon: "fas fa-brain", count: 0 },
        { id: "stories", label: "Stories", icon: "fas fa-book-open", count: 0 },
        { id: "tests", label: "Tests", icon: "fas fa-book" },
        { id: "settings", label: "Settings", icon: "fas fa-cog" },
      ],

      // Revision data
      revision: new Set(),
      revisionWords: [],
      revisionSearch: "",
      revisionSort: "recent",

      // Stories data
      userStories: [],
      storiesSearch: "",
      storiesFilter: "all",

      // Test data
      testHistory: [],
      newTest: {
        name: "",
        type: "flashcard",
        questionCount: 10,
        wordSource: "revision",
      },

      // Settings
      userSettings: {
        displayName: "John Doe",
        weeklyGoal: 20,
        dailyReminder: true,
        weeklyProgress: true,
        theme: "light",
      },

      // Recent activities
      recentActivities: [],

      // Search timeout for debouncing
      searchTimeout: null,
    };
  },
  created: async function () {
    // Handle URL hash for active tab
    this.handleUrlHash();
    
    // Check if token exists first - quick local check
    const token = localStorage.getItem("auth_token");
    if (!token) {
      console.log("No auth token found, user not authenticated");
      this.isLoading = false;
      return;
    }

    // If token exists, check authentication with server
    try {
      await this.check_is_auth();

      // Initialize dashboard only if user is authenticated
      if (this.is_auth) {
        await this.initializeDashboard();
      } else {
      }
    } catch (error) {
      console.error("Auth check failed:", error);
    }

    // Set loading to false after all checks
    this.isLoading = false;
  },

  mounted() {
    // Listen for hash changes
    window.addEventListener('hashchange', this.handleUrlHash);
  },

  beforeUnmount() {
    // Remove hash change listener
    window.removeEventListener('hashchange', this.handleUrlHash);
  },

  watch: {
    // Watch for revision search changes and debounce API calls
    revisionSearch() {
      this.handleRevisionSearch();
    },
  },

  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
      is_auth: "is_auth",
    }),
    filteredRevision() {
      let filtered = [...this.revisionWords];

      // Apply search filter
      if (this.revisionSearch) {
        const search = this.revisionSearch.toLowerCase();
        filtered = filtered.filter(
          (word) =>
            (word.word || word.english || "").toLowerCase().includes(search) || (word.definition || word.bangla || "").toLowerCase().includes(search)
        );
      }

      // Apply sorting
      switch (this.revisionSort) {
        case "alphabetical":
          filtered.sort((a, b) => (a.word || a.english).localeCompare(b.word || b.english));
          break;
        case "difficulty":
          filtered.sort((a, b) => (a.difficulty || 1) - (b.difficulty || 1));
          break;
        case "recent":
        default:
          // Already in recent order
          break;
      }

      return filtered;
    },

    filteredStories() {
      let filtered = [...this.userStories];

      // Apply search filter
      if (this.storiesSearch) {
        const search = this.storiesSearch.toLowerCase();
        filtered = filtered.filter((story) => story.title.toLowerCase().includes(search) || story.excerpt.toLowerCase().includes(search));
      }

      // Apply status filter
      switch (this.storiesFilter) {
        case "reading":
          filtered = filtered.filter((story) => story.readingProgress > 0 && story.readingProgress < 100);
          break;
        case "completed":
          filtered = filtered.filter((story) => story.isCompleted);
          break;
        case "bookmarked":
          filtered = filtered.filter((story) => story.isBookmarked);
          break;
        case "all":
        default:
          // Show all stories
          break;
      }

      return filtered;
    },
  },

  methods: {
    ...mapActions(auth_store, {
      check_is_auth: "check_is_auth",
      log_out: "log_out",
    }),

    handleUrlHash() {
      // Get hash from URL and set active tab
      const hash = window.location.hash.substring(1); // Remove #
      const validTabs = ['overview', 'revision', 'stories', 'tests', 'settings'];
      
      if (hash && validTabs.includes(hash)) {
        this.activeTab = hash;
        console.log('Active tab set from URL hash:', hash);
      }
    },

    switchTab(tabId) {
      // Set active tab
      this.activeTab = tabId;
      
      // Update URL hash
      window.history.replaceState(null, null, `#${tabId}`);
      
      console.log('Switched to tab:', tabId);
    },

    async initializeDashboard() {
      try {
        // Only initialize if user is authenticated
        // Template will handle redirects based on is_auth state
        if (!this.is_auth) {
          return;
        }

        // Load user data
        await this.loadUserData();

        // Load favorites from localStorage
        this.loadRevisionWords();

        // Load user stories
        await this.loadUserStories();

        // Load test history
        await this.loadTestHistory();

        // Load recent activities
        this.loadRecentActivities();

        // Update tab counts
        this.updateTabCounts();
      } catch (error) {
        console.error("Failed to initialize dashboard:", error);
      }
    },

    async loadUserData() {
      try {
        // Use auth info from store if available
        if (this.auth_info && Object.keys(this.auth_info).length > 0) {
          this.user = {
            name: this.auth_info.name || "User",
            email: this.auth_info.email || "user@example.com",
            avatar: this.auth_info.avatar || "/avatar.png",
          };
          this.userSettings.displayName = this.auth_info.name || "User";
        } else {
          // Fallback to localStorage
          const userData = JSON.parse(localStorage.getItem("user_data") || "{}");
          if (userData.name) {
            this.user = { ...this.user, ...userData };
            this.userSettings.displayName = userData.name;
          }
        }
      } catch (error) {
        console.error("Failed to load user data:", error);
      }
    },

    async loadRevisionWords() {
      try {
        // Use API to get user's revision vocabulary
        const response = await axios.get('/vocabularies/get-revision-vocabulary');

        if (response.data.status === 'success') {
          console.log("Revision words loaded from API:", response.data.data);

          this.revisionWords = response.data.data || [];
          this.userStats.totalRevision =  this.revisionWords.length || 0;
          
          // Update revision Set for compatibility with other components
          this.revision = new Set(this.revisionWords.map(word => word.id));
          
          // Save to localStorage for offline access
          localStorage.setItem("dictionary_revision", JSON.stringify([...this.revision]));
          
          // Update tab counts after loading
          this.updateTabCounts();
          
          console.log(`Loaded ${this.revisionWords.length} revision words from API, total count: ${this.userStats.totalRevision}`);
        } else {
          console.warn('Failed to load revision words:', response.data.message);
          // Fallback to localStorage method
          this.loadRevisionFromCache();
        }
      } catch (error) {
        console.error("Failed to load revision words from API:", error);
        // Fallback to localStorage method
        this.loadRevisionFromCache();
      }
    },

    // Fallback method for loading from cache
    async loadRevisionFromCache() {
      try {
        const cachedData = localStorage.getItem("vocabulary_cache");
        if (cachedData) {
          const vocabularyData = JSON.parse(cachedData);
          this.revisionWords = vocabularyData.filter((word) => this.revision.has(word.id));
        }
        this.userStats.totalRevision = this.revisionWords.length;
      } catch (error) {
        console.error("Failed to load revision words from cache:", error);
      }
    },

    async loadUserStories() {
      // Mock story data - replace with actual API call
      this.userStories = [
        {
          id: 1,
          title: "The Adventure of the Missing Dictionary",
          excerpt: "A thrilling tale about a scholar's quest to find a legendary dictionary...",
          image: "/dummy.png",
          wordCount: 1250,
          difficulty: "intermediate",
          readingProgress: 65,
          isCompleted: false,
          isBookmarked: true,
          addedAt: new Date(Date.now() - 86400000 * 2),
        },
        {
          id: 2,
          title: "Words That Changed History",
          excerpt: "Explore how powerful words shaped the course of human history...",
          image: "/dummy.png",
          wordCount: 890,
          difficulty: "advanced",
          readingProgress: 100,
          isCompleted: true,
          isBookmarked: false,
          addedAt: new Date(Date.now() - 86400000 * 5),
        },
        {
          id: 3,
          title: "The Etymology Chronicles",
          excerpt: "Journey through the fascinating origins of everyday words...",
          image: "/dummy.png",
          wordCount: 2100,
          difficulty: "beginner",
          readingProgress: 25,
          isCompleted: false,
          isBookmarked: true,
          addedAt: new Date(Date.now() - 86400000 * 1),
        },
      ];

      this.userStats.totalStories = this.userStories.length;
    },

    async loadTestHistory() {
      // Mock test history - replace with actual API call
      this.testHistory = [
        {
          id: 1,
          name: "Weekly Vocabulary Review",
          type: "multiple-choice",
          score: 85,
          totalQuestions: 20,
          correctAnswers: 17,
          completedAt: new Date(Date.now() - 86400000 * 1),
          duration: 12, // minutes
        },
        {
          id: 2,
          name: "Spelling Challenge",
          type: "spelling",
          score: 72,
          totalQuestions: 15,
          correctAnswers: 11,
          completedAt: new Date(Date.now() - 86400000 * 3),
          duration: 18,
        },
        {
          id: 3,
          name: "Favorite Words Quiz",
          type: "flashcard",
          score: 94,
          totalQuestions: 25,
          correctAnswers: 23,
          completedAt: new Date(Date.now() - 86400000 * 7),
          duration: 8,
        },
      ];

      this.userStats.testsCompleted = this.testHistory.length;
    },

    loadRecentActivities() {
      // Mock recent activities - replace with actual API call
      this.recentActivities = [
        {
          id: 1,
          type: "favorite",
          icon: "fas fa-heart",
          text: 'Added "eloquent" to favorites',
          timestamp: new Date(Date.now() - 3600000 * 2),
        },
        {
          id: 2,
          type: "test",
          icon: "fas fa-brain",
          text: "Completed Weekly Vocabulary Review with 85% score",
          timestamp: new Date(Date.now() - 86400000 * 1),
        },
        {
          id: 3,
          type: "story",
          icon: "fas fa-book-open",
          text: 'Started reading "The Adventure of the Missing Dictionary"',
          timestamp: new Date(Date.now() - 86400000 * 2),
        },
        {
          id: 4,
          type: "achievement",
          icon: "fas fa-trophy",
          text: "Achieved 7-day learning streak!",
          timestamp: new Date(Date.now() - 86400000 * 3),
        },
      ];

      // Calculate current streak
      this.userStats.currentStreak = 7;
    },

    updateTabCounts() {
      const favTab = this.tabs.find((tab) => tab.id === "revision");
      const storiesTab = this.tabs.find((tab) => tab.id === "stories");

      if (favTab) favTab.count = this.userStats.totalRevision;
      if (storiesTab) storiesTab.count = this.userStats.totalStories;
    },

    // Revision methods
    async removeRevision(wordId) {
      if (confirm("Remove this word from revision?")) {
        try {
          // Call API to remove from revision using axios with proper headers
          const response = await axios.post("/vocabularies/remove-from-revision", {
            vocabulary_id: wordId,
          });

          if (response.data.status === "success") {
            // Update local state
            this.revision.delete(wordId);
            this.revisionWords = this.revisionWords.filter((word) => word.id !== wordId);
            this.userStats.totalRevision = this.revisionWords.length;
            this.updateTabCounts();
            this.saveRevision();

            // Show success message
            window.s_alert("Word removed from revision list");
          } else {
            console.error("Failed to remove from revision:", response.data.message);
            window.s_alert("Failed to remove word from revision");
          }
        } catch (error) {
          console.error("Error removing from revision:", error);
          window.s_alert("Failed to remove word from revision");
        }
      }
    },

    // Search handler with debouncing for revision
    async handleRevisionSearch() {
      // Clear previous timeout
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }

      // Set new timeout for debounced search
      this.searchTimeout = setTimeout(async () => {
        await this.loadRevisionWords();
      }, 300);
    },

    saveRevision() {
      try {
        localStorage.setItem("dictionary_revision", JSON.stringify([...this.revision]));
      } catch (error) {
        console.error("Failed to save revision:", error);
      }
    },

    toggleWordDetails(wordId) {
      // Find the word and toggle its expanded state
      const word = this.revisionWords.find(w => w.id === wordId);
      if (word) {
        this.$set ? this.$set(word, '_expanded', !word._expanded) : (word._expanded = !word._expanded);
        
        // If expanding and details haven't been loaded, simulate loading
        if (word._expanded && !word._detailsLoaded) {
          this.$set ? this.$set(word, '_loadingDetails', true) : (word._loadingDetails = true);
          
          // Simulate API call delay
          setTimeout(() => {
            this.$set ? this.$set(word, '_loadingDetails', false) : (word._loadingDetails = false);
            this.$set ? this.$set(word, '_detailsLoaded', true) : (word._detailsLoaded = true);
          }, 1000);
        }
      }
    },

    getSynonyms(word) {
      if (!word.synonyms) return [];
      if (Array.isArray(word.synonyms)) return word.synonyms;
      if (typeof word.synonyms === 'string') {
        return word.synonyms.split(',').map(s => s.trim()).filter(s => s);
      }
      return [];
    },
    
    getAntonyms(word) {
      if (!word.antonyms) return [];
      if (Array.isArray(word.antonyms)) return word.antonyms;
      if (typeof word.antonyms === 'string') {
        return word.antonyms.split(',').map(s => s.trim()).filter(s => s);
      }
      return [];
    },
    
    getExampleSentences(word) {
      // Handle multiple possible field names
      const exampleField = word.example_sentence || word.examples || word.example_sentences;
      if (!exampleField) return [];
      
      if (Array.isArray(exampleField)) {
        return exampleField;
      }
      
      if (typeof exampleField === 'string') {
        try {
          const parsed = JSON.parse(exampleField);
          return Array.isArray(parsed) ? parsed : [{ sentence: exampleField }];
        } catch {
          return [{ sentence: exampleField }];
        }
      }
      
      return [];
    },

    getKeywords(word) {
      if (!word.keyword && !word.keywords) return [];
      
      const keywordField = word.keywords || word.keyword;
      if (Array.isArray(keywordField)) return keywordField;
      if (typeof keywordField === 'string') {
        return keywordField.split(',').map(k => k.trim()).filter(k => k);
      }
      return [];
    },

    searchKeyword(keyword) {
      // Handle keyword click - navigate to search or highlight
      console.log('Searching for keyword:', keyword);
      // You can implement navigation or search functionality here
      // For example: this.$router.push(`/vocabulary?search=${keyword}`);
    },

    highlightWordsInStory(storyContent, targetWord) {
      if (!storyContent) return '';
      if (!targetWord) return storyContent;

      try {
        const regex = new RegExp(`\\b(${targetWord})\\b`, 'gi');
        return storyContent.replace(regex, '<span class="highlighted-word">$1</span>');
      } catch (e) {
        return storyContent;
      }
    },

    testWord(word) {
      // Create a quick test for this specific word
      this.newTest = {
        name: `Quick Test: ${word.word || word.english}`,
        type: "flashcard",
        questionCount: 1,
        wordSource: "specific",
        specificWords: [word],
      };
      this.createCustomTest();
    },

    viewWord(word) {
      // Navigate to word details or open modal
      window.location.href = `/vocabulary?${word.id}`;
    },

    // Stories methods
    readStory(story) {
      // Navigate to story reading page
      this.$router.push(`/stories/${story.id}`);
    },

    removeStory(storyId) {
      if (confirm("Remove this story from your collection?")) {
        this.userStories = this.userStories.filter((story) => story.id !== storyId);
        this.userStats.totalStories = this.userStories.length;
        this.updateTabCounts();
      }
    },

    // Test methods
    startQuickTest(type) {
      const testConfig = {
        flashcard: { questions: 10, duration: 5 },
        "multiple-choice": { questions: 15, duration: 12 },
        spelling: { questions: 12, duration: 15 },
        sentence: { questions: 8, duration: 20 },
      };

      const config = testConfig[type];

      // Mock test start - replace with actual test implementation
      alert(`Starting ${type} test with ${config.questions} questions (${config.duration} min)`);

      // In a real implementation, you would navigate to the test page:
      // this.$router.push(`/test/start?type=${type}&questions=${config.questions}`);
    },

    createCustomTest() {
      if (!this.newTest.name.trim()) {
        alert("Please enter a test name");
        return;
      }

      // Mock test creation - replace with actual test creation logic
      console.log("Creating custom test:", this.newTest);
      alert(`Created test: ${this.newTest.name}`);

      this.showCreateTest = false;
      this.newTest = {
        name: "",
        type: "flashcard",
        questionCount: 10,
        wordSource: "favorites",
      };
    },

    viewTestDetails(test) {
      // Show test details modal or navigate to results page
      alert(`Test: ${test.name}\nScore: ${test.score}%\nCompleted: ${this.formatDate(test.completedAt)}`);
    },

    retakeTest(test) {
      if (confirm(`Retake "${test.name}"?`)) {
        // Start the test again
        this.startQuickTest(test.type);
      }
    },

    getScoreClass(score) {
      if (score >= 90) return "excellent";
      if (score >= 75) return "good";
      if (score >= 60) return "average";
      return "poor";
    },

    // Settings methods
    saveSettings() {
      try {
        localStorage.setItem("user_settings", JSON.stringify(this.userSettings));
        this.user.name = this.userSettings.displayName;
        alert("Settings saved successfully!");
      } catch (error) {
        console.error("Failed to save settings:", error);
        alert("Failed to save settings");
      }
    },

    resetSettings() {
      if (confirm("Reset all settings to default values?")) {
        this.userSettings = {
          displayName: this.user.name,
          weeklyGoal: 20,
          dailyReminder: true,
          weeklyProgress: true,
          theme: "light",
        };
      }
    },

    // Utility methods
    formatDate(date) {
      try {
        return new Date(date).toLocaleDateString();
      } catch {
        return "Invalid date";
      }
    },

    formatTimeAgo(timestamp) {
      const now = new Date();
      const time = new Date(timestamp);
      const diffInSeconds = Math.floor((now - time) / 1000);

      if (diffInSeconds < 60) return "Just now";
      if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} min ago`;
      if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
      if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)} days ago`;

      return this.formatDate(timestamp);
    },

    async retryAuth() {
      this.debugState.retrying = true;
      this.debugState.info = "Retrying authentication check...";

      try {
        console.log("[Profile] Retrying auth check...");
        await this.check_is_auth();

        if (this.is_auth) {
          this.debugState.info = "Authentication successful!";
          this.showDebug = false;
          await this.initializeDashboard();
        } else {
          this.debugState.info = "Still not authenticated. Check your login.";
        }
      } catch (error) {
        console.error("[Profile] Retry failed:", error);
        this.debugState.info = `Retry failed: ${error.message}`;
      } finally {
        this.debugState.retrying = false;
      }
    },

    getWordTypeClass(wordType) {
      // Return CSS class based on word type for colored badges
      const type = wordType?.toLowerCase();
      switch (type) {
        case 'noun':
          return 'noun-badge';
        case 'verb':
          return 'verb-badge';
        case 'adjective':
          return 'adjective-badge';
        case 'adverb':
          return 'adverb-badge';
        case 'preposition':
          return 'preposition-badge';
        case 'conjunction':
          return 'conjunction-badge';
        case 'interjection':
          return 'interjection-badge';
        default:
          return 'default-badge';
      }
    },

    searchSynonym(synonym) {
      // Handle synonym click - could navigate to the synonym word or search for it
      console.log('Searching for synonym:', synonym);
      // You can implement navigation to the synonym word here
      // For example: this.$router.push(`/vocabulary?search=${synonym}`);
    },

    searchAntonym(antonym) {
      // Handle antonym click - could navigate to the antonym word or search for it
      console.log('Searching for antonym:', antonym);
      // You can implement navigation to the antonym word here
      // For example: this.$router.push(`/vocabulary?search=${antonym}`);
    },

    highlightWords(text) {
      if (!text) return '';
      
      // List of vocabulary words to highlight (you can make this dynamic)
      const vocabularyWords = [
        'Assurance', 'Daunt', 'Frantic', 'Remonstrance', 'Requisite', 
        'Retort', 'Surpass', 'Cede', 'Plasticity', 'Upheal', 'Rescind'
      ];
      
      let highlightedText = text;
      
      vocabularyWords.forEach(word => {
        const regex = new RegExp(`\\b${word}\\b`, 'gi');
        highlightedText = highlightedText.replace(regex, `<span class="highlighted-word">${word}</span>`);
      });
      
      return highlightedText;
    },

    logout() {
      // Use auth store logout action which handles confirmation and cleanup
      this.log_out();
    },
  },
};
</script>

<style scoped>
/* ===== PROFILE DASHBOARD STYLES ===== */

/* Loading State */
.loading-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.loading-content {
  text-align: center;
  padding: 40px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.loading-content .spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f4f6;
  border-top: 4px solid #667eea;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

.loading-content h3 {
  color: #374151;
  margin-bottom: 10px;
}

.loading-content p {
  color: #6b7280;
  margin: 0;
}

/* Auth Required State */
.auth-required {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.auth-content {
  text-align: center;
  padding: 60px 40px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  max-width: 400px;
}

.auth-content i {
  color: #6b7280;
  margin-bottom: 20px;
}

.auth-content h3 {
  color: #374151;
  font-size: 1.5rem;
  margin-bottom: 16px;
}

.auth-content p {
  color: #6b7280;
  margin-bottom: 30px;
  line-height: 1.5;
}

/* Base Styles */
.profile-dashboard {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Header Section */
.dashboard-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 40px 0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 30px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 20px;
}

.user-avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-details h1 {
  margin: 0 0 8px 0;
  font-size: 2rem;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.user-email {
  margin: 0 0 16px 0;
  opacity: 0.9;
  font-size: 1.1rem;
}

.user-stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.95rem;
  opacity: 0.9;
}

.header-actions .btn {
  background: rgba(255, 255, 255, 0.2);
  border: 2px solid rgba(255, 255, 255, 0.3);
  color: white;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.header-actions .btn:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Navigation Tabs */
.dashboard-nav {
  background: white;
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.nav-tabs {
  display: flex;
  gap: 0;
  overflow-x: auto;
  padding: 0;
}

.nav-tab {
  background: none;
  border: none;
  padding: 20px 24px;
  color: #6b7280;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  display: flex;
  align-items: center;
  gap: 8px;
  white-space: nowrap;
  border-bottom: 3px solid transparent;
}

.nav-tab:hover {
  color: #667eea;
  background: #f8fafc;
}

.nav-tab.active {
  color: #667eea;
  border-bottom-color: #667eea;
  background: #f8fafc;
}

.tab-count {
  background: #667eea;
  color: white;
  font-size: 0.75rem;
  padding: 2px 8px;
  border-radius: 12px;
  min-width: 20px;
  text-align: center;
}

/* Dashboard Content */
.dashboard-content {
  padding: 40px 0;
}

.tab-content {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Cards */
.card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.card-header {
  padding: 24px 24px 16px;
  border-bottom: 1px solid #f3f4f6;
}

.card-header h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 10px;
}

.card-body {
  padding: 24px;
}

/* Overview Grid */
.overview-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: auto auto;
  gap: 30px;
  grid-template-areas:
    "progress stats"
    "activity activity";
}

.learning-progress {
  grid-area: progress;
}

.quick-stats {
  grid-area: stats;
}

.recent-activity {
  grid-area: activity;
}

/* Progress Stats */
.progress-stats {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.progress-item {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.progress-label {
  font-weight: 600;
  color: #374151;
  font-size: 0.95rem;
}

.progress-bar {
  height: 12px;
  background: #f3f4f6;
  border-radius: 6px;
  overflow: hidden;
  position: relative;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
  border-radius: 6px;
  transition: width 0.8s ease;
  position: relative;
}

.progress-fill.weekly {
  background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

.progress-value {
  font-weight: 700;
  color: #667eea;
  font-size: 0.9rem;
}

/* Quick Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px;
  background: #f8fafc;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.stat-card:hover {
  background: #f1f5f9;
  transform: translateY(-2px);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  color: white;
}

.stat-icon.revision {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}
.stat-icon.stories {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}
.stat-icon.tests {
  background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}
.stat-icon.streak {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.stat-number {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1f2937;
  line-height: 1;
}

.stat-label {
  font-size: 0.875rem;
  color: #6b7280;
  font-weight: 500;
}

/* Activity List */
.activity-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px 20px;
  background: #f8fafc;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.activity-item:hover {
  background: #f1f5f9;
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.9rem;
}

.activity-icon.favorite {
  background: #ef4444;
}
.activity-icon.test {
  background: #8b5cf6;
}
.activity-icon.story {
  background: #3b82f6;
}
.activity-icon.achievement {
  background: #f59e0b;
}

.activity-text {
  font-weight: 600;
  color: #374151;
  line-height: 1.4;
}

.activity-time {
  font-size: 0.875rem;
  color: #6b7280;
}

/* Section Headers */
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #f3f4f6;
}

.section-header h2 {
  margin: 0;
  font-size: 1.75rem;
  font-weight: 700;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 12px;
}

.section-actions {
  display: flex;
  align-items: center;
  gap: 16px;
}

/* Search and Sort */
.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box i {
  position: absolute;
  left: 12px;
  color: #9ca3af;
  z-index: 2;
}

.search-input {
  padding: 12px 16px 12px 40px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 0.95rem;
  background: white;
  transition: all 0.3s ease;
  min-width: 250px;
}

.search-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.sort-select {
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  background: white;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.sort-select:focus {
  outline: none;
  border-color: #667eea;
}

/* Revision Grid - Following VocabularyCard Structure */
.revision-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 20px;
}

.vocabulary-card {
  transition: transform 0.2s ease-in-out;
}

.vocabulary-card:hover {
  transform: translateY(-2px);
}

.word-card {
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  transition: all 0.3s ease;
  overflow: hidden;
}

.word-card:hover {
  box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.word-title-container {
  width: 100%;
  overflow: hidden;
}

.word-title {
  color: #2c3e50;
  font-weight: 600;
  font-size: 1.4rem;
  line-height: 1.3;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 0;
  display: inline-block;
  max-width: 100%;
}

.word-text {
  display: inline;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: calc(100% - 80px); /* Reserve space for badge */
}

.word-title .badge {
  display: inline-block;
  white-space: nowrap;
  font-size: 0.75rem;
  vertical-align: middle;
}

.meaning {
  color: #495057;
  font-size: 1.1rem;
  line-height: 1.4;
  font-weight: 500;
}

.word-actions {
  display: flex;
  gap: 8px;
  flex-shrink: 0;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.synonym-tag, .antonym-tag {
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.85rem;
  padding: 6px 10px;
  border-radius: 6px;
  font-weight: 500;
}

.synonym-tag:hover {
  background-color: var(--bs-success) !important;
  color: white !important;
  transform: scale(1.05);
}

.antonym-tag:hover {
  background-color: var(--bs-danger) !important;
  color: white !important;
  transform: scale(1.05);
}

.example-sentence {
  border-left: 4px solid #17a2b8;
  background: linear-gradient(90deg, rgba(23,162,184,0.1) 0%, rgba(255,255,255,1) 100%);
  transition: background-color 0.2s ease;
}

.example-sentence:hover {
  background: linear-gradient(90deg, rgba(23,162,184,0.15) 0%, rgba(248,249,250,1) 100%);
}

.example-sentence blockquote {
  font-style: italic;
  color: #2c3e50;
  margin-bottom: 8px;
  font-size: 1rem;
  line-height: 1.5;
}

.story-content {
  max-height: 300px;
  overflow-y: auto;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.story-content:hover {
  background-color: #f0f0f0 !important;
}

.keyword-badge {
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.85rem;
  padding: 6px 10px;
  border-radius: 6px;
  font-weight: 500;
}

.keyword-badge:hover {
  background-color: var(--bs-primary) !important;
  color: white !important;
  transform: scale(1.05);
}

.story-block {
  transition: background-color 0.2s ease;
  cursor: pointer;
  border: 1px solid #dee2e6;
}

.story-block:hover {
  background-color: #f8f9fa !important;
}

.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-bottom: 1px solid #dee2e6;
  padding: 1.25rem;
}

.card-body {
  padding: 1.25rem;
  background: white;
}

.btn {
  border-radius: 6px;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn:hover {
  transform: translateY(-1px);
}

.badge {
  font-size: 0.75rem;
  padding: 0.4em 0.6em;
  border-radius: 4px;
}

.highlighted-word {
  background: #fef3c7;
  color: #92400e;
  padding: 2px 4px;
  border-radius: 4px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.highlighted-word:hover {
  background: #f59e0b;
  color: white;
}

/* Loading animation */
.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}

/* Stories Grid */
.stories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 30px;
}

.story-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.story-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.story-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.story-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.story-card:hover .story-image img {
  transform: scale(1.05);
}

.story-progress {
  position: absolute;
  top: 16px;
  right: 16px;
}

.progress-circle {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 600;
}

.progress-circle.completed {
  background: #10b981;
}

.story-content {
  padding: 24px;
}

.story-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 12px 0;
  line-height: 1.3;
}

.story-excerpt {
  color: #6b7280;
  line-height: 1.5;
  margin-bottom: 16px;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.story-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.story-words {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #6b7280;
  font-size: 0.9rem;
}

.story-difficulty {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: capitalize;
}

.story-difficulty.beginner {
  background: #dcfce7;
  color: #166534;
}
.story-difficulty.intermediate {
  background: #fef3c7;
  color: #92400e;
}
.story-difficulty.advanced {
  background: #fecaca;
  color: #991b1b;
}

.story-actions {
  display: flex;
  gap: 8px;
}

/* Test Types */
.test-types {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
  margin-bottom: 40px;
}

.test-type-card {
  background: white;
  border-radius: 16px;
  padding: 30px 24px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.test-type-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  border-color: #667eea;
}

.test-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  margin: 0 auto 20px;
}

.test-type-card h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 12px 0;
}

.test-type-card p {
  color: #6b7280;
  line-height: 1.5;
  margin-bottom: 16px;
}

.test-meta {
  display: flex;
  justify-content: center;
  gap: 20px;
  font-size: 0.875rem;
  color: #9ca3af;
}

.test-meta span {
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Test History */
.test-history {
  background: white;
  border-radius: 16px;
  padding: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.test-history h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 24px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.test-history-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.test-history-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px;
  background: #f8fafc;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.test-history-item:hover {
  background: #f1f5f9;
}

.test-name {
  font-weight: 600;
  color: #374151;
  margin-bottom: 4px;
}

.test-date {
  font-size: 0.875rem;
  color: #6b7280;
}

.test-score {
  font-size: 1.25rem;
  font-weight: 700;
  padding: 8px 16px;
  border-radius: 20px;
  color: white;
}

.test-score.excellent {
  background: #10b981;
}
.test-score.good {
  background: #3b82f6;
}
.test-score.average {
  background: #f59e0b;
}
.test-score.poor {
  background: #ef4444;
}

.test-actions {
  display: flex;
  gap: 8px;
}

/* Settings */
.settings-content {
  max-width: 600px;
}

.settings-section {
  background: white;
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 24px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.settings-section h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 24px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.setting-item {
  margin-bottom: 20px;
}

.setting-item label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.setting-input,
.setting-select {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.setting-input:focus,
.setting-select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
  width: auto;
  margin: 0;
}

.settings-actions {
  display: flex;
  gap: 12px;
  margin-top: 30px;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  border: none;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  line-height: 1;
}

.btn:hover {
  transform: translateY(-2px);
  text-decoration: none;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
  color: white;
}

.btn-outline-primary {
  background: transparent;
  color: #667eea;
  border: 2px solid #667eea;
}

.btn-outline-primary:hover {
  background: #667eea;
  color: white;
}

.btn-outline-secondary {
  background: transparent;
  color: #6b7280;
  border: 2px solid #d1d5db;
}

.btn-outline-secondary:hover {
  background: #6b7280;
  color: white;
}

.btn-outline-danger {
  background: transparent;
  color: #ef4444;
  border: 2px solid #ef4444;
}

.btn-outline-danger:hover {
  background: #ef4444;
  color: white;
}

.btn-sm {
  padding: 8px 16px;
  font-size: 0.875rem;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.empty-state i {
  margin-bottom: 20px;
  color: #d1d5db;
}

.empty-state h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 12px;
}

.empty-state p {
  margin-bottom: 24px;
  line-height: 1.5;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 16px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
  padding: 24px 24px 16px;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
}

.modal-close {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 8px;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.modal-close:hover {
  background: #f3f4f6;
  color: #374151;
}

.modal-body {
  padding: 24px;
}

.modal-footer {
  padding: 16px 24px 24px;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Responsive Design */
@media (max-width: 1024px) {
  .overview-grid {
    grid-template-columns: 1fr;
    grid-template-areas:
      "progress"
      "stats"
      "activity";
  }

  .stats-grid {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 768px) {
  .container {
    padding: 0 16px;
  }

  .header-content {
    flex-direction: column;
    text-align: center;
    gap: 20px;
  }

  .user-info {
    flex-direction: column;
    text-align: center;
  }

  .user-stats {
    justify-content: center;
  }

  .nav-tabs {
    gap: 0;
    justify-content: flex-start;
  }

  .nav-tab {
    padding: 16px 20px;
    font-size: 0.9rem;
  }

  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .section-actions {
    width: 100%;
    flex-direction: column;
    gap: 12px;
  }

  .search-input {
    min-width: auto;
    width: 100%;
  }

  .revision-grid,
  .stories-grid {
    grid-template-columns: 2fr;
  }

  .test-types {
    grid-template-columns: 1fr;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .test-history-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .test-actions {
    align-self: flex-end;
  }
}

@media (max-width: 480px) {
  .dashboard-content {
    padding: 20px 0;
  }

  .user-avatar {
    width: 60px;
    height: 60px;
  }

  .user-details h1 {
    font-size: 1.5rem;
  }

  .user-stats {
    flex-direction: column;
    gap: 8px;
  }

  .nav-tab {
    padding: 12px 16px;
    font-size: 0.85rem;
  }

  .card-header,
  .card-body {
    padding: 20px;
  }

  .revision-card,
  .story-content {
    padding: 20px;
  }

  .modal-content {
    margin: 10px;
  }
}
</style>
