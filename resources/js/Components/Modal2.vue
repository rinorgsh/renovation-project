<script setup>
import { watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(['close']);

watch(() => props.show, () => {
  if (props.show) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = null;
  }
});

const close = () => {
  if (props.closeable) {
    emit('close');
  }
};

const closeOnEscape = (e) => {
  if (e.key === 'Escape' && props.show) {
    close();
  }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const maxWidthClass = {
  'sm': 'sm:max-w-sm',
  'md': 'sm:max-w-md',
  'lg': 'sm:max-w-lg',
  'xl': 'sm:max-w-xl',
  '2xl': 'sm:max-w-2xl',
};
</script>

<template>
  <teleport to="body">
    <transition name="modal-fade">
      <div v-if="show" class="modal-backdrop" @click="close">
        <transition name="modal-content-fade">
          <div
            v-if="show"
            class="modal-content"
            :class="maxWidthClass[maxWidth]"
            @click.stop
          >
            <div v-if="closeable" class="modal-close" @click="close">
              <i class="fas fa-times"></i>
            </div>
            
            <slot></slot>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  overflow-y: auto;
  padding: 2rem 1rem;
}

.modal-content {
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  padding: 1.5rem;
  max-width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
}

.modal-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  cursor: pointer;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background-color: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  z-index: 10;
}

.modal-close:hover {
  background-color: #e5e7eb;
}

/* Transitions */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-content-fade-enter-active {
  transition: all 0.3s ease-out;
}

.modal-content-fade-leave-active {
  transition: all 0.2s ease-in;
}

.modal-content-fade-enter-from,
.modal-content-fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

/* Responsive classes */
@media (min-width: 640px) {
  .sm\:max-w-sm {
    max-width: 24rem;
  }

  .sm\:max-w-md {
    max-width: 28rem;
  }

  .sm\:max-w-lg {
    max-width: 32rem;
  }

  .sm\:max-w-xl {
    max-width: 36rem;
  }

  .sm\:max-w-2xl {
    max-width: 42rem;
  }
}
</style>