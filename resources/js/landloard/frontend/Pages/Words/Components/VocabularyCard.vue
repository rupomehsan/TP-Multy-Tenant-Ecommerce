<template>
    <div class="vocabulary-card">
        <div class="card word-card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-start">
                <div class="word-header flex-grow-1">
                    <h4 class="word-title mb-2 text-capitalize">
                        <span class="me-2">{{ word.word || word.english || 'N/A' }}</span>
                        <span v-if="word.part_of_speech" class="badge bg-primary">{{ word.part_of_speech }}</span>
                    </h4>
                    <div class="word-meta">
                        <p class="meaning mb-1">{{ word.meaning || word.bangla || 'No meaning available' }}</p>
                        <small v-if="word.pronunciation" class="text-muted">
                            <i class="fas fa-volume-up me-1"></i>
                            {{ word.pronunciation }}
                        </small>
                    </div>
                </div>
                <div class="word-actions">
                    <button 
                        @click="toggleRevision"
                        class="btn btn-sm"
                        :class="isInRevision ? 'btn-warning' : 'btn-outline-warning'"
                        :title="isInRevision ? 'Remove from revision' : 'Add to revision'"
                        :disabled="revisionLoading"
                    >
                        <i v-if="revisionLoading" class="fas fa-spinner fa-spin"></i>
                        <i v-else class="fas fa-brain"></i>
                    </button>
                    <button 
                        @click="$emit('toggle-details', word.id)"
                        class="btn btn-sm btn-outline-primary"
                        :title="isExpanded ? 'Hide details' : 'Show details'"
                    >
                        <i class="fas" :class="isExpanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                </div>
            </div>
            
            <!-- Expanded Details -->
            <div v-if="isExpanded" class="card-body">
                <!-- Loading State -->
                <div v-if="word._loadingDetails" class="text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                    <small class="text-muted ms-2">Loading details...</small>
                </div>
                
                <!-- Word Details -->
                <div v-else>
                    <!-- Synonyms -->
                    <div v-if="synonyms.length > 0" class="mb-3">
                        <h6 class="fw-bold text-success">
                            <i class="fas fa-plus-circle me-2"></i>
                            Synonyms
                        </h6>
                        <div class="tags-container">
                            <span 
                                v-for="synonym in synonyms" 
                                :key="synonym"
                                @click="$emit('keyword-click', synonym, word)"
                                class="badge bg-success-subtle text-success synonym-tag me-1 mb-1"
                            >
                                {{ synonym }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Antonyms -->
                    <div v-if="antonyms.length > 0" class="mb-3">
                        <h6 class="fw-bold text-danger">
                            <i class="fas fa-minus-circle me-2"></i>
                            Antonyms
                        </h6>
                        <div class="tags-container">
                            <span 
                                v-for="antonym in antonyms" 
                                :key="antonym"
                                @click="$emit('keyword-click', antonym, word)"
                                class="badge bg-danger-subtle text-danger antonym-tag me-1 mb-1"
                            >
                                {{ antonym }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Example Sentences -->
                    <div v-if="exampleSentences.length > 0" class="mb-3">
                        <h6 class="fw-bold text-info">
                            <i class="fas fa-quote-left me-2"></i>
                            Example Sentences
                        </h6>
                        <div v-for="(example, index) in exampleSentences" :key="index" class="example-sentence p-3 mb-2 rounded">
                            <blockquote class="mb-1">"{{ example.sentence || example }}"</blockquote>
                            <small v-if="example.meaning" class="text-muted">
                                ({{ example.meaning }})
                            </small>
                            <small v-else-if="example.translation" class="text-muted">
                                ({{ example.translation }})
                            </small>
                        </div>
                    </div>
                    
                    <!-- Story Content -->
                    <div v-if="(word.stories && word.stories.length) || word.story || word.story_html" class="mb-3">
                        <h6 class="fw-bold text-primary">
                            <i class="fas fa-book me-2"></i>
                            Story
                        </h6>

                        <!-- Stories array from API (primary) -->
                        <div v-if="word.stories && word.stories.length">
                            <div 
                                v-for="(storyItem, index) in word.stories" 
                                :key="index"
                                class="story-block mb-3 p-3 bg-white rounded border"
                                @click="handleStoryClick"
                                v-html="getHighlightedStoryHtml(storyItem.story, word.word || word.english, word._selectedKeyword)"
                            ></div>
                        </div>

                        <!-- Fallback: single story HTML field -->
                        <div v-else-if="word.story || word.story_html" class="story-block mb-3 p-3 bg-white rounded border">
                            <div 
                                @click="handleStoryClick"
                                v-html="highlightedStoryHtml"
                                class="story-content"
                            ></div>
                        </div>
                    </div>

                    <!-- Keywords -->
                    <div v-if="keywordsList.length > 0" class="mb-3">
                        <h6 class="fw-bold text-secondary">
                            <i class="fas fa-tags me-2"></i>
                            Keywords
                        </h6>
                        <div class="tags-container">
                            <span 
                                v-for="keyword in keywordsList" 
                                :key="keyword"
                                @click="$emit('keyword-click', keyword, word)"
                                class="badge bg-secondary keyword-badge me-1 mb-1"
                                :class="{ 'bg-warning text-dark': word._selectedKeyword === keyword }"
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
</template>

<script>
import { privateAxios } from '../../../Plugins/axios_setup';

export default {
    name: 'VocabularyCard',
    props: {
        word: {
            type: Object,
            required: true
        },
        isExpanded: {
            type: Boolean,
            default: false
        },
        isInRevision: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            revisionLoading: false
        };
    },
    emits: ['toggle-details', 'revision-updated', 'keyword-click'],
    computed: {
        synonyms() {
            if (!this.word.synonyms) return [];
            return this.word.synonyms.split(',').map(s => s.trim()).filter(s => s);
        },
        
        antonyms() {
            if (!this.word.antonyms) return [];
            return this.word.antonyms.split(',').map(s => s.trim()).filter(s => s);
        },
        
        exampleSentences() {
            if (!this.word.example_sentence && !this.word.examples) return [];
            
            // Handle example_sentence field (from API response)
            if (this.word.example_sentence) {
                if (Array.isArray(this.word.example_sentence)) {
                    return this.word.example_sentence;
                }
                if (typeof this.word.example_sentence === 'string') {
                    try {
                        const parsed = JSON.parse(this.word.example_sentence);
                        return Array.isArray(parsed) ? parsed : [{ sentence: this.word.example_sentence }];
                    } catch {
                        return [{ sentence: this.word.example_sentence }];
                    }
                }
            }
            
            // Handle examples field (fallback)
            if (this.word.examples) {
                if (Array.isArray(this.word.examples)) {
                    return this.word.examples;
                }
                if (typeof this.word.examples === 'string') {
                    try {
                        const parsed = JSON.parse(this.word.examples);
                        return Array.isArray(parsed) ? parsed : [{ sentence: this.word.examples }];
                    } catch {
                        return [{ sentence: this.word.examples }];
                    }
                }
            }
            
            return [];
        },

        keywordsList() {
            if (!this.word.keyword) return [];
            if (typeof this.word.keyword === 'string') {
                return this.word.keyword.split(',').map(k => k.trim()).filter(k => k);
            }
            if (Array.isArray(this.word.keyword)) {
                return this.word.keyword;
            }
            return [];
        },
        
        highlightedStoryHtml() {
            const storyContent = this.word.story_html || this.word.story;
            if (!storyContent) return '';
            
            return this.getHighlightedStoryHtml(
                storyContent, 
                this.word.word || this.word.english, 
                this.word._selectedKeyword
            );
        }
    },
    methods: {
        async toggleRevision() {
            if (this.revisionLoading) return;
            
            this.revisionLoading = true;
            
            try {
                const endpoint = this.isInRevision ? 'remove-from-revision' : 'add-to-revision';
                const action = this.isInRevision ? 'removed from' : 'added to';
                
                // Using the custom privateAxios function with GET request and vocabulary_id parameter
                const apiUrl = `vocabularies/${endpoint}?vocabulary_id=${this.word.id}`;
                const response = await privateAxios(apiUrl, 'get');
                
                if (response && response.status === 'success') {
                    // Show success message
                    window.s_alert(`Word ${action} revision successfully`);
                    
                    // Emit event to parent component to update the revision status
                    this.$emit('revision-updated', {
                        wordId: this.word.id,
                        isInRevision: !this.isInRevision,
                        word: this.word
                    });
                } else {
                    throw new Error(response?.message || 'Failed to update revision');
                }
                
            } catch (error) {
                console.error('Error toggling revision:', error);
                const action = this.isInRevision ? 'removing from' : 'adding to';
                
                if (error.response && error.response.status === 401) {
                    window.s_alert('Please login to manage your revision list', 'error');
                } else if (error.response && error.response.data && error.response.data.message) {
                    window.s_alert(error.response.data.message, 'error');
                } else if (error.message) {
                    window.s_alert(error.message, 'error');
                } else {
                    window.s_alert(`Failed ${action} revision. Please try again.`, 'error');
                }
            } finally {
                this.revisionLoading = false;
            }
        },
        
        formatDate(dateString) {
            try {
                return new Date(dateString).toLocaleDateString();
            } catch {
                return dateString;
            }
        },
        
        handleStoryClick(event) {
            const el = event.target;
            const storyWordEl = el.closest && el.closest('.story-word');
            const matched = storyWordEl || (el.closest && el.closest('.matched-word'));
            
            if (matched) {
                const keyword = (matched.getAttribute && matched.getAttribute('data-word')) || 
                               (matched.textContent && matched.textContent.trim());
                if (keyword) {
                    // Update selected keyword on this word
                    this.$set ? this.$set(this.word, '_selectedKeyword', keyword) : (this.word._selectedKeyword = keyword);
                    this.$emit('keyword-click', keyword, this.word);
                }
            }
        },
        
        getHighlightedStoryHtml(storyHtml, matchWord, selectedKeyword) {
            if (!storyHtml) return '';
            if (!matchWord && !selectedKeyword) return storyHtml;

            try {
                const parser = new DOMParser();
                const doc = parser.parseFromString(storyHtml, 'text/html');
                const target = selectedKeyword || matchWord;
                const walker = doc.createTreeWalker(doc.body, NodeFilter.SHOW_TEXT, null, false);
                const nodesToReplace = [];

                while (walker.nextNode()) {
                    const node = walker.currentNode;
                    if (node.nodeValue && /[A-Za-z]/.test(node.nodeValue)) {
                        nodesToReplace.push(node);
                    }
                }

                nodesToReplace.forEach(textNode => {
                    const text = textNode.nodeValue;
                    const replaced = text.replace(/([A-Za-z]+)/g, (m) => {
                        const isMatch = target && m.toLowerCase() === target.toLowerCase();
                        const isSelected = selectedKeyword && m.toLowerCase() === selectedKeyword.toLowerCase();
                        const classes = ['story-word'];
                        if (isMatch) classes.push('matched-word');
                        if (isSelected) classes.push('matched-clicked');
                        const safe = m.replace(/"/g, '&quot;');
                        return `<span class="${classes.join(' ')}" data-word="${safe}">${m}</span>`;
                    });
                    const span = doc.createElement('span');
                    span.innerHTML = replaced;
                    textNode.parentNode.replaceChild(span, textNode);
                });

                return doc.body.innerHTML;
            } catch (e) {
                try {
                    const target = selectedKeyword || matchWord;
                    const re = new RegExp(`\\b(${this.escapeRegExp(target)})\\b`, 'gi');
                    const replacementClass = selectedKeyword ? 'matched-word matched-clicked' : 'matched-word';
                    return storyHtml.replace(re, `<span class="${replacementClass}">$1</span>`);
                } catch (_) {
                    return storyHtml;
                }
            }
        },
        
        escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\\]\\\\]/g, '\\$&');
        }
    }
};
</script>

<style scoped>
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

.word-title {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.4rem;
    line-height: 1.3;
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

/* Highlight matched words inside story HTML */
:deep(.matched-word) {
    color: #c82333;
    font-weight: 700;
    background-color: rgba(220, 53, 69, 0.1);
    padding: 2px 4px;
    border-radius: 3px;
    cursor: pointer;
}

:deep(.matched-clicked) {
    text-decoration: underline;
    background-color: rgba(220, 53, 69, 0.2);
}

:deep(.story-word) {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

:deep(.story-word:hover) {
    background-color: rgba(0, 123, 255, 0.1);
    border-radius: 2px;
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

.story-content {
    line-height: 1.6;
    font-size: 0.95rem;
}

:deep(.story-word) {
    cursor: pointer;
    transition: background-color 0.1s ease;
}

:deep(.story-word:hover) {
    background-color: rgba(13, 110, 253, 0.1);
    border-radius: 2px;
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .word-actions {
        margin-top: 1rem;
        align-self: flex-end;
        width: 100%;
        justify-content: flex-end;
    }
    
    .word-title {
        font-size: 1.25rem;
    }
    
    .meaning {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .tags-container {
        gap: 4px;
    }
    
    .synonym-tag, .antonym-tag {
        font-size: 0.8rem;
        padding: 4px 8px;
    }
}

/* Loading animation */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

/* Accessibility improvements */
.btn:focus,
.synonym-tag:focus,
.antonym-tag:focus {
    outline: 2px solid #0d6efd;
    outline-offset: 2px;
}

/* Print styles */
@media print {
    .word-actions {
        display: none;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
