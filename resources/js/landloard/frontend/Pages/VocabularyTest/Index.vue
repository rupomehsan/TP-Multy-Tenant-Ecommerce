<template>
    <div class="vocabulary-test-container">
        <!-- Header Section -->
        <div class="header-section">
            <h1 class="page-title">
                <i class="fas fa-graduation-cap"></i>
                Vocabulary Test
            </h1>
            <p class="page-description">
                Test your vocabulary knowledge with English-to-Bangla and Bangla-to-English translations! Select a translation direction, choose a difficulty level, and click on words to reveal their meanings.
            </p>
        </div>

        <!-- Language Direction Toggle -->
        <div class="language-section">
            <h3 class="language-title">
                <i class="fas fa-language"></i>
                Translation Direction
            </h3>
            <div class="language-toggle">
                <button 
                    @click="toggleLanguageDirection" 
                    class="language-toggle-btn"
                    :class="{ active: true }"
                >
                    <div class="language-display">
                        <span class="from-lang">
                            <i :class="currentDirection.fromIcon"></i>
                            {{ currentDirection.fromLabel }}
                        </span>
                        <div class="arrow-container">
                            <i class="fas fa-exchange-alt toggle-arrow"></i>
                        </div>
                        <span class="to-lang">
                            <i :class="currentDirection.toIcon"></i>
                            {{ currentDirection.toLabel }}
                        </span>
                    </div>
                </button>
                <div class="direction-description">
                    {{ currentDirection.description }}
                </div>
            </div>
        </div>

        <!-- Level Filter Section -->
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-filter"></i>
                Select Level
            </h3>
            <div class="level-filters">
                <label 
                    v-for="level in availableLevels" 
                    :key="level.value" 
                    class="level-filter-item"
                    :class="{ active: selectedLevel === level.value }"
                >
                    <input 
                        type="radio" 
                        :value="level.value" 
                        v-model="selectedLevel" 
                        @change="fetchVocabulary"
                    />
                    <span class="filter-label">
                        <i :class="level.icon"></i>
                        {{ level.label }}
                    </span>
                    <span class="word-count" v-if="levelCounts[level.value]">
                        ({{ levelCounts[level.value] }})
                    </span>
                </label>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-section">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading vocabulary...</span>
            </div>
        </div>

        <!-- Error State -->
        <div v-if="error" class="error-section">
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ error }}</span>
                <button @click="fetchVocabulary" class="retry-btn">
                    <i class="fas fa-redo"></i>
                    Retry
                </button>
            </div>
        </div>

        <!-- Vocabulary Grid -->
        <div v-if="!loading && !error && filteredVocabulary.length > 0" class="vocabulary-grid">
            <div class="grid-header">
                <div class="header-word">
                    <i :class="currentDirection.fromIcon"></i>
                    {{ currentDirection.fromLabel }} Word
                </div>
                <div class="header-meaning">
                    <i :class="currentDirection.toIcon"></i>
                    {{ currentDirection.toLabel }} Meaning (Click to reveal)
                </div>
            </div>

            <div 
                v-for="word in filteredVocabulary" 
                :key="word.id" 
                class="vocabulary-row"
                :class="{ 'meaning-revealed': revealedMeanings.includes(word.id) }"
            >
                <div class="word-column">
                    <div class="word-content">
                        <h4 class="word-text">{{ getDisplayWord(word) }}</h4>
                        <div class="word-meta">
                            <span v-if="word.pronunciation && languageDirection === 'e2b'" class="pronunciation">
                                <i class="fas fa-volume-up"></i>
                                /{{ word.pronunciation }}/
                            </span>
                            <span v-if="word.part_of_speech" class="part-of-speech">
                                <i class="fas fa-tag"></i>
                                {{ word.part_of_speech }}
                            </span>
                            <span class="word-level" :class="'level-' + word.label">
                                <i class="fas fa-star"></i>
                                {{ word.label }}
                            </span>
                            <span class="word-language" :class="'lang-' + getSourceLanguage(word)">
                                <i :class="getLanguageIcon(getSourceLanguage(word))"></i>
                                {{ getLanguageLabel(getSourceLanguage(word)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div 
                    class="meaning-column" 
                    @click="toggleMeaning(word.id)"
                >
                    <div class="meaning-content">
                        <div v-if="!revealedMeanings.includes(word.id)" class="meaning-hidden">
                            <i class="fas fa-eye"></i>
                            <span>Click to reveal meaning</span>
                        </div>
                        <div v-else class="meaning-revealed">
                            <div class="meaning-text">
                                <i class="fas fa-quote-left"></i>
                                {{ getDisplayMeaning(word) }}
                            </div>
                            
                            <!-- Additional Bangla Meaning (when translating from English) -->
                            <div v-if="languageDirection === 'e2b' && word.bangla_meaning && word.bangla_meaning !== word.meaning" class="bangla-meaning">
                                <h5>
                                    <i class="fas fa-language"></i>
                                    অতিরিক্ত বাংলা অর্থ:
                                </h5>
                                <div class="bangla-text">{{ word.bangla_meaning }}</div>
                            </div>

                            <!-- English Word Details (when translating from Bangla) -->
                            <div v-if="languageDirection === 'b2e'" class="english-details">
                                <div class="english-word-info">
                                    <h5>
                                        <i class="fas fa-language"></i>
                                        English Word Details:
                                    </h5>
                                    <div class="word-details">
                                        <span v-if="word.pronunciation" class="pronunciation">
                                            <i class="fas fa-volume-up"></i>
                                            Pronunciation: /{{ word.pronunciation }}/
                                        </span>
                                        <span v-if="word.part_of_speech" class="part-of-speech">
                                            <i class="fas fa-tag"></i>
                                            Part of Speech: {{ word.part_of_speech }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Example Sentences -->
                            <div v-if="word.example_sentence && word.example_sentence.length > 0" class="example-sentences">
                                <h5>
                                    <i class="fas fa-comment"></i>
                                    Examples:
                                </h5>
                                <ul>
                                    <li v-for="(example, index) in getExampleSentences(word)" :key="index">
                                        <span v-if="typeof example === 'object' && example.meaning">
                                            "{{ example.meaning }}"
                                        </span>
                                        <span v-else>
                                            "{{ example }}"
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Synonyms -->
                            <div v-if="getSynonyms(word).length > 0" class="synonyms">
                                <h5>
                                    <i class="fas fa-equals"></i>
                                    Synonyms:
                                </h5>
                                <div class="synonym-tags">
                                    <span v-for="(synonym, index) in getSynonyms(word)" :key="index" class="synonym-tag">
                                        {{ synonym }}
                                    </span>
                                </div>
                            </div>

                            <!-- Antonyms -->
                            <div v-if="getAntonyms(word).length > 0" class="antonyms">
                                <h5>
                                    <i class="fas fa-not-equal"></i>
                                    Antonyms:
                                </h5>
                                <div class="antonym-tags">
                                    <span v-for="(antonym, index) in getAntonyms(word)" :key="index" class="antonym-tag">
                                        {{ antonym }}
                                    </span>
                                </div>
                            </div>

                            <div class="meaning-actions">
                                <button @click.stop="toggleMeaning(word.id)" class="hide-btn">
                                    <i class="fas fa-eye-slash"></i>
                                    Hide
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!loading && !error && filteredVocabulary.length === 0" class="empty-state">
            <div class="empty-content">
                <i class="fas fa-search"></i>
                <h3>No vocabulary found</h3>
                <p v-if="selectedLevel">No words found for the "{{ selectedLevel }}" level.</p>
                <p v-else>Please select a level to view vocabulary words.</p>
            </div>
        </div>

        <!-- Statistics Section -->
        <div v-if="!loading && filteredVocabulary.length > 0" class="statistics-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-list"></i>
                    <div class="stat-content">
                        <span class="stat-number">{{ filteredVocabulary.length }}</span>
                        <span class="stat-label">Total Words</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-eye"></i>
                    <div class="stat-content">
                        <span class="stat-number">{{ revealedMeanings.length }}</span>
                        <span class="stat-label">Meanings Revealed</span>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-percentage"></i>
                    <div class="stat-content">
                        <span class="stat-number">{{ progressPercentage }}%</span>
                        <span class="stat-label">Progress</span>
                    </div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" :style="{ width: progressPercentage + '%' }"></div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div v-if="!loading && filteredVocabulary.length > 0" class="action-section">
            <button @click="revealAllMeanings" class="action-btn reveal-all-btn">
                <i class="fas fa-eye"></i>
                Reveal All Meanings
            </button>
            <button @click="hideAllMeanings" class="action-btn hide-all-btn">
                <i class="fas fa-eye-slash"></i>
                Hide All Meanings
            </button>
            <button @click="resetProgress" class="action-btn reset-btn">
                <i class="fas fa-refresh"></i>
                Reset Progress
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'VocabularyTestIndex',
    data() {
        return {
            vocabulary: [],
            selectedLevel: 'basic',
            revealedMeanings: [],
            loading: false,
            error: null,
            languageDirection: 'e2b', // 'e2b' for English to Bangla, 'b2e' for Bangla to English
            availableLevels: [
                { value: 'basic', label: 'Basic', icon: 'fas fa-seedling' },
                { value: 'medium', label: 'Medium', icon: 'fas fa-leaf' },
                { value: 'advance', label: 'Advanced', icon: 'fas fa-tree' },
                { value: 'professional', label: 'Professional', icon: 'fas fa-crown' }
            ],
            languageDirections: {
                'e2b': {
                    fromLabel: 'English',
                    toLabel: 'বাংলা',
                    fromIcon: 'fas fa-flag-usa',
                    toIcon: 'fas fa-flag',
                    description: 'See English words and reveal their Bangla meanings'
                },
                'b2e': {
                    fromLabel: 'বাংলা',
                    toLabel: 'English',
                    fromIcon: 'fas fa-flag',
                    toIcon: 'fas fa-flag-usa',
                    description: 'See Bangla words and reveal their English meanings'
                }
            }
        }
    },

    computed: {
        filteredVocabulary() {
            if (!this.selectedLevel) return [];
            return this.vocabulary.filter(word => word.label === this.selectedLevel);
        },

        levelCounts() {
            const counts = {};
            this.availableLevels.forEach(level => {
                counts[level.value] = this.vocabulary.filter(word => word.label === level.value).length;
            });
            return counts;
        },

        progressPercentage() {
            if (this.filteredVocabulary.length === 0) return 0;
            return Math.round((this.revealedMeanings.length / this.filteredVocabulary.length) * 100);
        },

        currentDirection() {
            return this.languageDirections[this.languageDirection];
        }
    },

    mounted() {
        this.fetchVocabulary();
    },

    methods: {
        async fetchVocabulary() {
            this.loading = true;
            this.error = null;

            try {
                const response = await window.publicAxios('vocabularies', 'get');
                
                if (response.status === 'success' && response.data) {
                    this.vocabulary = response.data.data || response.data;
                    
                    // Parse JSON fields if they exist
                    this.vocabulary = this.vocabulary.map(word => ({
                        ...word,
                        example_sentence: this.parseJsonField(word.example_sentence),
                        synonyms: this.parseJsonField(word.synonyms),
                        antonyms: this.parseJsonField(word.antonyms),
                        keyword: this.parseJsonField(word.keyword)
                    }));
                } else {
                    this.error = 'Failed to load vocabulary data';
                }
            } catch (error) {
                console.error('Error fetching vocabulary:', error);
                this.error = error.response?.data?.message || 'Failed to load vocabulary data';
            } finally {
                this.loading = false;
            }
        },

        parseJsonField(field) {
            if (!field) return [];
            if (typeof field === 'string') {
                try {
                    return JSON.parse(field);
                } catch (e) {
                    return [];
                }
            }
            if (Array.isArray(field)) return field;
            return [];
        },

        toggleMeaning(wordId) {
            const index = this.revealedMeanings.indexOf(wordId);
            if (index > -1) {
                this.revealedMeanings.splice(index, 1);
            } else {
                this.revealedMeanings.push(wordId);
            }
        },

        revealAllMeanings() {
            this.revealedMeanings = this.filteredVocabulary.map(word => word.id);
        },

        hideAllMeanings() {
            this.revealedMeanings = [];
        },

        resetProgress() {
            this.revealedMeanings = [];
            this.selectedLevel = 'basic';
        },

        toggleLanguageDirection() {
            this.languageDirection = this.languageDirection === 'e2b' ? 'b2e' : 'e2b';
            this.revealedMeanings = []; // Reset revealed meanings when switching direction
        },

        getDisplayWord(word) {
            if (this.languageDirection === 'e2b') {
                return word.word; // Show English word
            } else {
                // For B2E: Show the meaning property (which contains Bangla text)
                return word.meaning || word.bangla_word || word.word;
            }
        },

        getDisplayMeaning(word) {
            if (this.languageDirection === 'e2b') {
                return word.bangla_meaning || word.meaning; // Show Bangla meaning if available
            } else {
                // For B2E: Show the English word as the "meaning" to be revealed
                return word.word;
            }
        },

        getSourceLanguage(word) {
            return this.languageDirection === 'e2b' ? 'english' : 'bangla';
        },

        getLanguageIcon(language) {
            return language === 'english' ? 'fas fa-flag-usa' : 'fas fa-flag';
        },

        getLanguageLabel(language) {
            return language === 'english' ? 'English' : 'বাংলা';
        },

        getExampleSentences(word) {
            if (!word.example_sentence) return [];
            if (Array.isArray(word.example_sentence)) return word.example_sentence;
            return [];
        },

        getSynonyms(word) {
            if (!word.synonyms) return [];
            if (Array.isArray(word.synonyms)) return word.synonyms;
            if (typeof word.synonyms === 'string') {
                // Handle comma-separated string format
                return word.synonyms.split(',').map(s => s.trim()).filter(s => s.length > 0);
            }
            return [];
        },

        getAntonyms(word) {
            if (!word.antonyms) return [];
            if (Array.isArray(word.antonyms)) return word.antonyms;
            if (typeof word.antonyms === 'string') {
                // Handle comma-separated string format
                return word.antonyms.split(',').map(s => s.trim()).filter(s => s.length > 0);
            }
            return [];
        }
    }
}
</script>

<style scoped>
.vocabulary-test-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header Section */
.header-section {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.page-description {
    font-size: 1.1rem;
    margin: 0;
    opacity: 0.9;
    line-height: 1.6;
}

/* Filter Section */
.language-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.language-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0 0 20px 0;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 10px;
}

.language-toggle {
    text-align: center;
}

.language-toggle-btn {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border: none;
    border-radius: 12px;
    padding: 20px 30px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    color: white;
    font-weight: 500;
    min-width: 300px;
}

.language-toggle-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
}

.language-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
}

.from-lang, .to-lang {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}

.arrow-container {
    display: flex;
    align-items: center;
}

.toggle-arrow {
    font-size: 1.2rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}

.direction-description {
    margin-top: 15px;
    color: #64748b;
    font-size: 0.95rem;
    font-style: italic;
}

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.filter-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0 0 20px 0;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 10px;
}

.level-filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.level-filter-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.level-filter-item:hover {
    border-color: #667eea;
    background: #eef2ff;
    transform: translateY(-2px);
}

.level-filter-item.active {
    border-color: #667eea;
    background: #667eea;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.level-filter-item input[type="radio"] {
    display: none;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    flex: 1;
}

.word-count {
    font-size: 0.9rem;
    opacity: 0.8;
    font-weight: 400;
}

/* Loading and Error States */
.loading-section, .error-section {
    text-align: center;
    padding: 60px 20px;
}

.loading-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    font-size: 1.1rem;
    color: #667eea;
}

.loading-spinner i {
    font-size: 1.5rem;
}

.error-message {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    color: #e53e3e;
    font-size: 1.1rem;
}

.retry-btn {
    padding: 8px 16px;
    background: #e53e3e;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background 0.3s ease;
}

.retry-btn:hover {
    background: #c53030;
}

/* Vocabulary Grid */
.vocabulary-grid {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    margin-bottom: 30px;
}

.grid-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

.header-word, .header-meaning {
    padding: 20px 25px;
    font-weight: 600;
    font-size: 1.1rem;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-meaning {
    border-left: 1px solid #e2e8f0;
}

.vocabulary-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.vocabulary-row:hover {
    background: #f8fafc;
}

.vocabulary-row.meaning-revealed {
    background: #eef2ff;
}

.word-column, .meaning-column {
    padding: 25px;
}

.meaning-column {
    border-left: 1px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.meaning-column:hover {
    background: #f0f4ff;
}

.word-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.word-text {
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 15px 0;
    color: #2d3748;
}

.word-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 0.9rem;
}

.pronunciation, .part-of-speech, .word-level {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.85rem;
}

.pronunciation {
    background: #e6fffa;
    color: #0f766e;
}

.part-of-speech {
    background: #fef3c7;
    color: #a16207;
}

.word-level {
    font-weight: 500;
}

.word-language {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
}

.lang-english {
    background: #dbeafe;
    color: #1e40af;
}

.lang-bangla {
    background: #d1fae5;
    color: #065f46;
}

.level-basic { background: #dcfce7; color: #166534; }
.level-medium { background: #fef3c7; color: #a16207; }
.level-advance { background: #fef2f2; color: #dc2626; }
.level-professional { background: #f3e8ff; color: #7c3aed; }

.meaning-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 100px;
}

.meaning-hidden {
    text-align: center;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-style: italic;
    padding: 20px;
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
}

.meaning-revealed {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.meaning-text {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 15px;
    color: #2d3748;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.meaning-text i {
    margin-top: 2px;
    color: #667eea;
}

.bangla-meaning, .english-meaning, .english-details {
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 8px;
    background: #f8fafc;
    border-left: 4px solid #667eea;
}

.bangla-meaning h5, .english-meaning h5, .english-details h5 {
    font-size: 0.9rem;
    margin: 0 0 10px 0;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 6px;
}

.bangla-text {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #2d3748;
    font-family: 'SolaimanLipi', 'Kalpurush', Arial, sans-serif;
}

.english-text {
    font-size: 1rem;
    line-height: 1.6;
    color: #2d3748;
}

.english-word-info {
    background: #fff;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.word-details {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
}

.word-details .pronunciation,
.word-details .part-of-speech {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.85rem;
    background: #f1f5f9;
    color: #475569;
}

.example-sentences, .synonyms, .antonyms {
    margin-bottom: 15px;
}

.example-sentences h5, .synonyms h5, .antonyms h5 {
    font-size: 0.9rem;
    margin: 0 0 8px 0;
    color: #4a5568;
    display: flex;
    align-items: center;
    gap: 6px;
}

.example-sentences ul {
    margin: 0;
    padding-left: 20px;
}

.example-sentences li {
    margin-bottom: 5px;
    font-style: italic;
    color: #64748b;
}

.synonym-tags, .antonym-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.synonym-tag, .antonym-tag {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 500;
}

.synonym-tag {
    background: #e0f2fe;
    color: #0369a1;
}

.antonym-tag {
    background: #fef2f2;
    color: #dc2626;
}

.meaning-actions {
    margin-top: 15px;
    text-align: right;
}

.hide-btn {
    padding: 6px 12px;
    background: #6b7280;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background 0.3s ease;
}

.hide-btn:hover {
    background: #4b5563;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #64748b;
}

.empty-content i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: #cbd5e0;
}

.empty-content h3 {
    font-size: 1.5rem;
    margin: 0 0 10px 0;
    color: #4a5568;
}

/* Statistics Section */
.statistics-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
}

.stat-item i {
    font-size: 1.5rem;
    color: #667eea;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-label {
    font-size: 0.9rem;
    color: #64748b;
}

.progress-bar {
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s ease;
}

/* Action Section */
.action-section {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    padding: 20px 0;
}

.action-btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.reveal-all-btn {
    background: #10b981;
    color: white;
}

.reveal-all-btn:hover {
    background: #059669;
    transform: translateY(-2px);
}

.hide-all-btn {
    background: #f59e0b;
    color: white;
}

.hide-all-btn:hover {
    background: #d97706;
    transform: translateY(-2px);
}

.reset-btn {
    background: #6b7280;
    color: white;
}

.reset-btn:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .vocabulary-test-container {
        padding: 15px;
    }

    .page-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 10px;
    }

    .level-filters {
        grid-template-columns: 1fr;
    }

    .language-toggle-btn {
        min-width: unset;
        width: 100%;
    }

    .language-display {
        flex-direction: column;
        gap: 10px;
    }

    .from-lang, .to-lang {
        justify-content: center;
    }

    .vocabulary-row, .grid-header {
        grid-template-columns: 1fr;
    }

    .header-meaning, .meaning-column {
        border-left: none;
        border-top: 1px solid #e2e8f0;
    }

    .word-column {
        border-bottom: 1px solid #e2e8f0;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .action-section {
        flex-direction: column;
        align-items: center;
    }

    .action-btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }

    .word-meta {
        flex-direction: column;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .filter-section, .vocabulary-grid, .statistics-section, .language-section {
        margin-left: -15px;
        margin-right: -15px;
        border-radius: 0;
    }

    .word-column, .meaning-column {
        padding: 20px 15px;
    }

    .language-display {
        gap: 8px;
    }

    .from-lang, .to-lang {
        font-size: 1rem;
    }
}
</style>

