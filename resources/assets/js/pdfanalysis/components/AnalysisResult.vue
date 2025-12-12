<template>
  <div class="analysis-message" role="listitem">
    <div class="cb-content-wrapper">
      <div class="cb-bubble">
        <div class="md" v-html="summaryHtml"></div>
      </div>

      <div v-if="isShowContent" class="cb-bubble original-content-bubble">
        <details>
          <summary>Xem nội dung gốc</summary>
          <pre>{{ result.content }}</pre>
        </details>
      </div>
    </div>
  </div>
</template>

<script>
import DetailTooltip from "./DetailTooltip.vue";
import markdownit from "markdown-it";
import hljs from "highlight.js";

export default {
  name: "AnalysisResult",
  components: {
    DetailTooltip,
  },
  props: {
    result: { type: Object, required: true },
    isShowContent: { type: Boolean, default: true },
  },
  data() {
    return { md: null, _chipVMs: [], showDetails: true };
  },
  mounted() {
    this.md = markdownit({
      html: false,
      linkify: true,
      breaks: true,
      highlight(str, lang) {
        if (lang && hljs.getLanguage(lang)) {
          try {
            return `<pre class="hljs"><code>${
              hljs.highlight(str, { language: lang, ignoreIllegals: true })
                .value
            }</code></pre>`;
          } catch (e) {}
        }
        return `<pre class="hljs"><code>${markdownit().utils.escapeHtml(
          str
        )}</code></pre>`;
      },
    });
    this.$nextTick(this.injectDetailButtons);
  },
  computed: {
    summaryHtml() {
      if (!this.result || !this.result.summary) return "";
      return this.md
        ? this.md.render(this.result.summary)
        : this.result.summary.replace(/\n/g, "<br>");
    },
    evidenceMap() {
      const normalize = (s) => (s || "").toString().trim().replace(/\s+/g, " ");
      const map = Object.create(null);

      (this.result.json || []).forEach((item) => {
        const { source, confidence } = item;
        Object.keys(item).forEach((k) => {
          if (k === "source" || k === "confidence") return;
          const v = item[k];
          if (v == null || v === "") return;

          const valNorm = normalize(v);
          if (!map[k]) map[k] = new Map();

          const prev = map[k].get(valNorm);
          if (!prev || (confidence || 0) > (prev.confidence || 0)) {
            map[k].set(valNorm, { source, confidence, rawValue: v });
          }
        });
      });

      return map;
    },
  },
  watch: {
    "result.summary"() {
      this.$nextTick(this.injectDetailButtons);
    },
    "result.json": {
      handler() {
        this.$nextTick(this.injectDetailButtons);
      },
      deep: true,
    },
  },
  methods: {
    normalizeText(s) {
      return (s || "").toString().trim().replace(/\s+/g, " ");
    },
    _removeOldChips(root) {
      // root.querySelectorAll('.detail-chip, .detail-popover, .detail-wrap').forEach(el => el.remove());
      (this._chipVMs || []).forEach((vm) => {
        try {
          vm.$destroy();
        } catch (e) {}
      });
      this._chipVMs = [];
      root.querySelectorAll(".detail-wrap").forEach((el) => el.remove());
    },
    _makeChip(meta) {
      if (!this.showDetails) return document.createTextNode("");
      const mountPoint = document.createElement("span");
      mountPoint.className = "detail-wrap";
      const vm = new Vue({
        render: (h) => h(DetailTooltip, { props: { meta } }),
      });
      vm.$mount();
      this._chipVMs.push(vm);
      mountPoint.appendChild(vm.$el);
      return mountPoint;
    },
    // _makeChip(meta) {
    //   const btn = document.createElement('button');
    //   btn.type = 'button';
    //   btn.className = 'detail-chip';
    //   btn.innerHTML = '<i class="fa fa-file-text"></i>';
    //   btn.setAttribute('aria-haspopup', 'dialog');
    //   btn.setAttribute('aria-expanded', 'false');
    //   btn.title = meta.confidence != null ? `Độ tin cậy: ${(meta.confidence * 100).toFixed(1)}%` : 'Chi tiết';

    //   const pop = document.createElement('span');
    //   pop.className = 'detail-popover';
    //   pop.setAttribute('role', 'dialog');
    //   pop.hidden = true;
    //   pop.innerHTML = `
    //   <div class="detail-popover__inner">
    //     <div class="detail-popover__row"><strong>Độ tin cậy:</strong> ${meta.confidence != null ? (meta.confidence * 100).toFixed(1) + '%' : '—'}</div>
    //     <div class="detail-popover__row"><strong>Nguồn:</strong></div>
    //     <pre class="detail-popover__source">${((meta && meta.source) || '').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>
    //   </div>
    // `;

    //   let hoverTimer = null;
    //   btn.addEventListener('mouseenter', () => {
    //     clearTimeout(hoverTimer);
    //     btn.setAttribute('aria-expanded', 'true');
    //     pop.hidden = false;
    //   });
    //   btn.addEventListener('mouseleave', () => {
    //     hoverTimer = setTimeout(() => {
    //       btn.setAttribute('aria-expanded', 'false');
    //       pop.hidden = true;
    //     }, 120);
    //   });
    //   pop.addEventListener('mouseenter', () => clearTimeout(hoverTimer));
    //   pop.addEventListener('mouseleave', () => {
    //     hoverTimer = setTimeout(() => {
    //       btn.setAttribute('aria-expanded', 'false');
    //       pop.hidden = true;
    //     }, 120);
    //   });
    //   btn.addEventListener('click', (e) => {
    //     e.stopPropagation();
    //     const isOpen = btn.getAttribute('aria-expanded') === 'true';
    //     btn.setAttribute('aria-expanded', String(!isOpen));
    //     pop.hidden = isOpen;
    //   });

    //   const wrap = document.createElement('span');
    //   wrap.className = 'detail-wrap';
    //   wrap.appendChild(btn);
    //   wrap.appendChild(pop);
    //   return wrap;
    // },
    _onOutsideClick(e) {
      const root = this.$el.querySelector(".md");
      if (!root) return;
      if (root.contains(e.target)) return;
      // root.querySelectorAll('.detail-chip[aria-expanded="true"]').forEach(btn => btn.setAttribute('aria-expanded', 'false'));
      // root.querySelectorAll('.detail-popover').forEach(pop => pop.hidden = true);
      window.dispatchEvent(new CustomEvent("detail-tooltip:close-all"));
    },
    _matchEvidence(key, valueOrBlockText) {
      const pool =
        this.evidenceMap && this.evidenceMap[key]
          ? this.evidenceMap[key]
          : null;
      if (!pool) return null;
      const norm = this.normalizeText(valueOrBlockText);

      if (pool.has(norm)) return pool.get(norm); // exact match

      // fuzzy contains 2 chiều (phù hợp case header + bullet con)
      let best = null,
        bestScore = -1;
      pool.forEach((meta, vNorm) => {
        const hit = vNorm.includes(norm) || norm.includes(vNorm);
        var conf = meta && meta.confidence != null ? meta.confidence : 0;
        if (hit && conf > bestScore) {
          best = meta;
          bestScore = conf;
        }
      });
      return best;
    },
    // injectDetailButtons() {
    //   const root = this.$el.querySelector('.md');
    //   if (!root) return;

    //   this._removeOldChips(root);

    //   const KEYS = Object.keys(this.evidenceMap || {});
    //   if (!KEYS.length) return;

    //   const blocks = root.querySelectorAll('p, li');
    //   const escape = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    //   blocks.forEach((el, idx) => {
    //     const text = el.innerText || '';

    //     KEYS.forEach((KEY) => {
    //       const keyRe = new RegExp(`^\\s*(?:[-–—•*]\\s*)?${escape(KEY)}\\s*:\\s*(.+)$`, 'i');
    //       const headerOnlyRe = new RegExp(`^\\s*(?:[-–—•*]\\s*)?${escape(KEY)}\\s*:\\s*$`, 'i');

    //       // Case 1: "- Key: Value"
    //       const m1 = text.match(keyRe);
    //       if (m1 && m1[1]) {
    //         const val = this.normalizeText(m1[1]);
    //         const meta = this._matchEvidence(KEY, val);
    //         if (meta) el.appendChild(this._makeChip(meta));
    //         return;
    //       }

    //       // Case 2: "- Key:" → gom block con
    //       const m2 = text.match(headerOnlyRe);
    //       if (m2) {
    //         let collected = [];
    //         for (let j = idx + 1; j < blocks.length; j++) {
    //           const sib = blocks[j];
    //           const sibTxt = sib.innerText || '';

    //           const isNewTopKey = KEYS.some(k =>
    //             new RegExp(`^\\s*(?:[-–—•*]\\s*)?${escape(k)}\\s*:\\s*`, 'i').test(sibTxt)
    //           );
    //           if (isNewTopKey) break;

    //           if (/^\s*[-*•]\s+/.test(sibTxt) || /^\s{2,}\S/.test(sibTxt)) {
    //             collected.push(sibTxt.trim());
    //           } else if (!sibTxt.trim()) {
    //             break;
    //           } else {
    //             collected.push(sibTxt.trim());
    //           }
    //         }

    //         const blockText = collected.length === 1 ? collected[0] : '';
    //         if (!blockText) return;
    //         if (blockText) {
    //           const meta = this._matchEvidence(KEY, blockText);
    //           if (meta) el.appendChild(this._makeChip(meta));
    //         }
    //       }
    //     });
    //   });
    // }
    injectDetailButtons() {
      const root = this.$el.querySelector(".md");
      if (!root) return;

      this._removeOldChips(root);

      const KEYS = Object.keys(this.evidenceMap || {});
      if (!KEYS.length) return;

      const blocks = Array.from(root.querySelectorAll("p, li, h1, h2, h3, h4"));
      const escape = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");

      const usedEvidence = new Set();

      // Helper function để gắn chip vào đúng vị trí
      const attachChip = (el, meta) => {
        // Bắt đầu từ element gốc (ví dụ: <li>)
        let attachmentTarget = el;
        // Lặn sâu vào phần tử con cuối cùng để gắn chip vào vị trí inline
        // Điều này xử lý trường hợp <li><p>text</p></li>
        while (attachmentTarget.lastElementChild) {
          // Tránh vòng lặp vô hạn nếu có lỗi DOM
          if (attachmentTarget.lastElementChild === attachmentTarget) break;
          attachmentTarget = attachmentTarget.lastElementChild;
        }
        attachmentTarget.appendChild(this._makeChip(meta));
      };

      // ===================================================================
      // PASS 1: Ưu tiên gắn chip cho các dòng có dữ liệu cụ thể nhất.
      // ===================================================================
      blocks.forEach((el) => {
        const text = el.innerText || "";
        if (!text.trim()) return;

        let meta = null;

        // Case 1: Dòng khớp hoàn toàn "Key: Value"
        for (const KEY of KEYS) {
          const keyRe = new RegExp(
            `^\\s*(?:[-–—•*]\\s*)?${escape(KEY)}\\s*:\\s*(.+)$`,
            "i"
          );
          const m1 = text.match(keyRe);
          if (m1 && m1[1]) {
            meta = this._matchEvidence(KEY, m1[1]);
            if (meta) break;
          }
        }

        // Case 3 (Fallback): Dòng là một phần của một value đã biết
        if (!meta) {
          const blockTextNorm = this.normalizeText(text);
          for (const KEY of KEYS) {
            const valueMap = this.evidenceMap[KEY];
            for (const [valNorm, evidenceMeta] of valueMap.entries()) {
              // Chỉ khớp nếu giá trị trong JSON chứa text của block này
              if (valNorm.includes(blockTextNorm)) {
                meta = evidenceMeta;
                break;
              }
            }
            if (meta) break;
          }
        }

        if (meta && !usedEvidence.has(meta)) {
          attachChip(el, meta); // Sử dụng helper function
          usedEvidence.add(meta);
        }
      });

      // ===================================================================
      // PASS 2: Chỉ xử lý các dòng header ("Key:") nếu bằng chứng
      // của chúng chưa được sử dụng ở Pass 1.
      // ===================================================================
      blocks.forEach((el, idx) => {
        if (el.querySelector(".detail-wrap")) return;
        const text = el.innerText || "";
        if (!text.trim()) return;

        for (const KEY of KEYS) {
          const headerOnlyRe = new RegExp(
            `^\\s*(?:[-–—•*]\\s*)?${escape(KEY)}\\s*:\\s*$`,
            "i"
          );
          if (headerOnlyRe.test(text)) {
            let collected = [];
            for (let j = idx + 1; j < blocks.length; j++) {
              const sib = blocks[j];
              const sibTxt = sib.innerText || "";
              const isNewTopKey = KEYS.some((k) =>
                new RegExp(
                  `^\\s*(?:[-–—•*]\\s*)?${escape(k)}\\s*:\\s*`,
                  "i"
                ).test(sibTxt)
              );
              if (isNewTopKey) break;
              if (
                /^\s*[-*•]\s+/.test(sibTxt) ||
                /^\s{2,}\S/.test(sibTxt) ||
                !sibTxt.trim()
              ) {
                if (sibTxt.trim()) collected.push(sibTxt.trim());
              } else {
                break;
              }
            }

            if (collected.length > 0) {
              const blockText = collected.join("\n");
              const meta = this._matchEvidence(KEY, blockText);
              if (meta && !usedEvidence.has(meta)) {
                attachChip(el, meta); // Sử dụng helper function
                usedEvidence.add(meta);
                break;
              }
            }
          }
        }
      });
    },
  },
  beforeDestroy() {
    document.removeEventListener("click", this._onOutsideClick, true);
  },
};
</script>

<style scoped>
.analysis-message {
  display: flex;
  gap: 12px;
  align-items: flex-start;
  margin: 12px auto;
  width: 100%;
  max-width: 750px;
}

.cb-content-wrapper {
  display: flex;
  flex-direction: column;
  min-width: 0;
  width: 100%;
}

.cb-bubble {
  padding: 12px 16px;
  border-radius: 18px;
  word-wrap: break-word;
  background: #fff;
  border: 1px solid #e0e0e0;
  color: #333;
  margin-bottom: 10px;
}

.original-content-bubble {
  background-color: #fff;
}

.original-content-bubble summary {
  cursor: pointer;
  font-weight: bold;
  color: #001f3f;
  display: flex;
  justify-content: space-between;
  align-items: center;
  list-style: none;
}

.original-content-bubble summary::after {
  content: "";
  display: inline-block;
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 6px solid #001f3f;
  transition: transform 0.2s ease-in-out;
}

.original-content-bubble details[open] > summary::after {
  transform: rotate(180deg);
}

.original-content-bubble pre {
  margin-top: 10px;
  white-space: pre-wrap;
  word-wrap: break-word;
  /* font-family: 'Courier New', Courier, monospace; */
  font-size: 1.25rem;
  max-height: 400px;
  overflow-y: auto;
  background-color: #fff;
  padding: 10px;
  border-radius: 5px;
}

.md >>> p {
  margin: 0 0 0.5em 0;
}

.md >>> p:last-child {
  margin-bottom: 0;
}

.md >>> ul,
.md >>> ol {
  padding-left: 20px;
}

.md >>> pre {
  padding: 12px;
  border-radius: 10px;
  overflow: auto;
}

.md >>> code {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
  font-size: 14px;
}

.md >>> strong {
  color: #001f3f;
}
</style>
