<template>
  <!-- Loading overlay -->
  <div v-if="isLoading" class="modal-overlay" aria-live="polite" aria-busy="true">
    <div class="loading-spinner" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>

  <!-- Error message -->
  <div v-if="errorMessage" class="error-message" role="alert">
    {{ errorMessage }}
    <button @click="errorMessage = ''" aria-label="Dismiss error message" class="dismiss-error">×</button>
  </div>

  <!-- Modal dialog -->
  <div v-if="showModal" role="dialog" aria-modal="true" :aria-labelledby="'modal-title-' + uniqueId">
    <div class="modal-overlay" @click.self="hideModal">
      <div 
        class="modal-content text-gray-700" 
        :style="{ width: cardWidth }" 
        tabindex="0"
        ref="modalContent"
      >
        <div class="modal-title" :id="'modal-title-' + uniqueId">{{ modalContent.title }}</div>
        <div class="modal-body py-5 px-2 mb-2 mt-2" v-html="sanitizedBody"></div>

        <div class="flex justify-between mt-5 bg-gray-100">
          <div class="px-4 py-1">
            <button 
              @click="hideModal" 
              class="close-button"
              aria-label="Close modal"
            >
              {{ closeButtonText }}
            </button>
          </div>
          <div class="px-4 py-1">
            <label class="flex items-center cursor-pointer">
              <input 
                type="checkbox" 
                @change="doNotShowAgain" 
                class="form-checkbox h-5 w-5 text-blue-600"
                aria-label="Do not show this popup again"
              >
              <span class="ml-2">{{ doNotShowAgainText }}</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    card: {
      type: Object,
      required: true,
      validator: function(obj) {
        return obj.hasOwnProperty('width') && obj.hasOwnProperty('name');
      }
    }
  },

  data() {
    return {
      showModal: false,
      isLoading: false,
      errorMessage: '',
      popup_card_id: null,
      width: this.card.width,
      name: this.card.name,
      modalContent: {
        title: "",
        body: "",
      },
      uniqueId: 'popup-' + Date.now(),
      closeButtonText: 'Close',
      doNotShowAgainText: 'Do Not Show Again'
    };
  },

  computed: {
    cardWidth() {
      // Map the Nova width values to CSS width values
      const widthMap = {
        '1/4': '25%',
        '1/3': '33.33%',
        '1/2': '50%',
        '2/3': '66.66%',
        '3/4': '75%',
        full: '100%',
      };
      return widthMap[this.card.width] || '50%';
    },

    // Basic HTML sanitization (for production, consider using a library like DOMPurify)
    sanitizedBody() {
      return this.modalContent.body;
    }
  },

  created() {
    this.checkIfModalShouldShow();

    // Get button text from config if available
    this.fetchConfig();
  },

  mounted() {
    // Add keyboard event listener for accessibility
    window.addEventListener('keydown', this.handleKeyDown);
  },

  beforeUnmount() {
    // Remove event listener when component is destroyed
    window.removeEventListener('keydown', this.handleKeyDown);
  },

  methods: {
    fetchConfig() {
      // This would ideally come from the backend, but for now we'll use defaults
      this.closeButtonText = 'Close';
      this.doNotShowAgainText = 'Do Not Show Again';
    },

    handleKeyDown(event) {
      // Close modal on Escape key
      if (event.key === 'Escape' && this.showModal) {
        this.hideModal();
      }
    },

    checkIfModalShouldShow() {
      this.isLoading = true;
      this.errorMessage = '';

      // Make API request
      axios.get("/api/modal-content/" + this.name)
        .then(({data}) => {
          this.isLoading = false;

          if (data.show_modal) {
            this.showModal = true;
            this.popup_card_id = data.popup_card_id ?? null;

            this.modalContent = {
              title: data.title || "Notification",
              body: data.body || "This is a notification from the system.",
            };

            // Focus the modal content for accessibility
            this.$nextTick(() => {
              if (this.$refs.modalContent) {
                this.$refs.modalContent.focus();
              }
            });
          }
        })
        .catch((error) => {
          this.isLoading = false;
          this.errorMessage = "Could not load popup content. Please try again later.";
          console.error("Error fetching modal content:", error.response || error.message);
        });
    },

    hideModal() {
      this.showModal = false;
    },

    doNotShowAgain() {
      if (!this.popup_card_id) {
        this.errorMessage = "Could not save your preference. The popup ID is missing.";
        return;
      }

      this.isLoading = true;

      // Mark modal as seen with an API request
      axios.post("/api/mark-modal-seen", {popup_card_id: this.popup_card_id})
        .then(() => {
          this.isLoading = false;
          this.showModal = false;
        })
        .catch((error) => {
          this.isLoading = false;
          this.errorMessage = "Could not save your preference. Please try again later.";
          console.error("Error marking modal seen:", error.response || error.message);
        });
    }
  }
};
</script>

<style>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.modal-content {
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  max-width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  outline: none; /* Remove focus outline, we'll add our own */
}

.modal-content:focus {
  box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5); /* Accessible focus indicator */
}

.modal-title {
  font-size: 1.5rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 1rem;
}

.modal-body {
  font-size: 1rem;
  line-height: 1.5;
}

.close-button, .do-not-show-button {
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  background-color: #f7fafc;
  border: 1px solid #e2e8f0;
  cursor: pointer;
  transition: background-color 0.2s;
}

.close-button:hover, .do-not-show-button:hover {
  background-color: #edf2f7;
}

.close-button:focus, .do-not-show-button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}

/* Loading spinner */
.loading-spinner {
  display: inline-block;
  width: 50px;
  height: 50px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

/* Error message */
.error-message {
  position: fixed;
  top: 1rem;
  right: 1rem;
  background-color: #fed7d7;
  color: #9b2c2c;
  padding: 1rem;
  border-radius: 0.25rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  z-index: 10000;
  display: flex;
  align-items: center;
  max-width: 24rem;
}

.dismiss-error {
  margin-left: 0.5rem;
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  color: #9b2c2c;
}

/* Checkbox styling */
.form-checkbox {
  appearance: none;
  -webkit-appearance: none;
  border: 1px solid #e2e8f0;
  border-radius: 0.25rem;
  background-color: #f7fafc;
  cursor: pointer;
  transition: background-color 0.2s, border-color 0.2s;
}

.form-checkbox:checked {
  background-color: #4299e1;
  border-color: #4299e1;
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M5.707 7.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L7 8.586 5.707 7.293z'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
}

.form-checkbox:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}
</style>
