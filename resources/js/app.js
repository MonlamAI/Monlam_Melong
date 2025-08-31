import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Idle-aware heartbeat: mark user active if any interaction in last 5 minutes
(() => {
  const IDLE_MS = 5 * 60 * 1000; // 5 minutes
  const BEAT_MS = 60 * 1000; // 1 minute
  let lastActivity = Date.now();

  const markActivity = () => { lastActivity = Date.now(); };
  ['mousemove', 'keydown', 'scroll', 'touchstart', 'click'].forEach(evt => {
    window.addEventListener(evt, markActivity, { passive: true });
  });

  async function sendHeartbeat() {
    const idle = Date.now() - lastActivity > IDLE_MS;
    const hidden = document.hidden;
    if (idle || hidden) return;
    try {
      await window.axios.post('/heartbeat');
    } catch (e) {
      // Silently ignore to avoid console noise
    }
  }

  setInterval(sendHeartbeat, BEAT_MS);
})();
