/**
 * Mortgage Approval Estimator — vanilla JS, no dependencies.
 * One question at a time; updates SVG gauge and score after each answer.
 */
(function () {
  'use strict';

  var CONFIG = window.finaramMaeConfig || {};
  var BASE_SCORE = CONFIG.baseScore != null ? CONFIG.baseScore : 50;
  var MIN_SCORE = CONFIG.minScore != null ? CONFIG.minScore : 0;
  var MAX_SCORE = CONFIG.maxScore != null ? CONFIG.maxScore : 95;

  /** Circumference for r=88 (matches SVG). */
  var GAUGE_CIRCUMFERENCE = 2 * Math.PI * 88;

  /**
   * Questionnaire definition — value keys map to scoring rules.
   */
  var QUESTIONS = [
    {
      id: 'ltv',
      label: 'Step 1 of 5',
      question: 'How much of the property price will you finance with a mortgage?',
      options: [
        { value: 'ltv_80', label: 'Up to 80%' },
        { value: 'ltv_80_90', label: '80–90%' },
        { value: 'ltv_90', label: '90%' },
      ],
    },
    {
      id: 'age',
      label: 'Step 2 of 5',
      question: 'What is your age?',
      options: [
        { value: 'age_under_36', label: 'Under 36' },
        { value: 'age_36_plus', label: '36+' },
      ],
    },
    {
      id: 'citizenship',
      label: 'Step 3 of 5',
      question: 'What is your citizenship?',
      options: [
        { value: 'citizenship_cz', label: 'Czech Republic' },
        { value: 'citizenship_eu', label: 'EU country' },
        { value: 'citizenship_non_eu', label: 'Non-EU country' },
        { value: 'citizenship_high_risk', label: 'High-risk country' },
      ],
    },
    {
      id: 'residency',
      label: 'Step 4 of 5',
      question: 'What is your residency status in the Czech Republic?',
      options: [
        { value: 'residency_cz_citizen', label: 'Czech citizen' },
        { value: 'residency_permanent', label: 'Permanent residence' },
        { value: 'residency_long_term', label: 'Long-term residence' },
        { value: 'residency_temporary', label: 'Temporary residence' },
        { value: 'residency_none', label: 'No residence in Czech Republic' },
      ],
    },
    {
      id: 'income',
      label: 'Step 5 of 5',
      question: 'What is your main source of income?',
      options: [
        { value: 'income_cz_employment', label: 'CZ full-time employment' },
        { value: 'income_osvc', label: 'OSVČ / Self-employed' },
        { value: 'income_company_owner', label: 'Company owner' },
        { value: 'income_rental', label: 'Rental income' },
        { value: 'income_eu_salary', label: 'EU salary abroad' },
        { value: 'income_non_eu_salary', label: 'Non-EU salary' },
        { value: 'income_business_abroad', label: 'Business income abroad' },
      ],
    },
  ];

  var SCORE_MAP = {
    ltv_80: 12,
    ltv_80_90: 6,
    age_under_36: 10,
    age_36_plus: 0,
    citizenship_cz: 10,
    citizenship_eu: 8,
    citizenship_non_eu: 0,
    citizenship_high_risk: -15,
    residency_cz_citizen: 12,
    residency_permanent: 12,
    residency_long_term: 9,
    residency_temporary: -5,
    residency_none: -15,
    income_cz_employment: 12,
    income_osvc: 8,
    income_company_owner: 6,
    income_rental: 6,
    income_eu_salary: 4,
    income_non_eu_salary: -10,
    income_business_abroad: -14,
  };

  /**
   * @param {Record<string, string>} answers
   * @returns {number}
   */
  function calculateScore(answers) {
    var score = BASE_SCORE;

    if (answers.ltv === 'ltv_80') {
      score += 12;
    } else if (answers.ltv === 'ltv_80_90') {
      score += 6;
    } else if (answers.ltv === 'ltv_90') {
      if (answers.age === 'age_36_plus') {
        score -= 15;
      }
      /* 90% + under 36: +0 LTV component until age is known */
    }

    if (answers.age === 'age_under_36') {
      score += 10;
    }

    if (answers.citizenship && SCORE_MAP[answers.citizenship] != null) {
      score += SCORE_MAP[answers.citizenship];
    }
    if (answers.residency && SCORE_MAP[answers.residency] != null) {
      score += SCORE_MAP[answers.residency];
    }
    if (answers.income && SCORE_MAP[answers.income] != null) {
      score += SCORE_MAP[answers.income];
    }

    return Math.max(MIN_SCORE, Math.min(MAX_SCORE, score));
  }

  /**
   * @param {HTMLElement} root
   */
  function MortgageApprovalEstimator(root) {
    this.root = root;
    this.answers = {};
    this.currentStep = 0;
    this.score = BASE_SCORE;

    this.stepsEl = root.querySelector('[data-mae-steps]');
    this.finalEl = root.querySelector('[data-mae-final]');
    this.progressBar = root.querySelector('[data-mae-progress-bar]');
    this.finalScoreEl = root.querySelector('[data-mae-final-score]');

    this.gaugeRoots = root.querySelectorAll('.mae__gauge-wrap');
    this.gaugeFills = root.querySelectorAll('[data-mae-gauge-fill]');
    this.gaugeValues = root.querySelectorAll('[data-mae-gauge-value]');

    this.init();
  }

  MortgageApprovalEstimator.prototype.init = function () {
    this.renderStep(0);
    this.updateGauge(this.score, false);
    this.updateProgress();
  };

  MortgageApprovalEstimator.prototype.renderStep = function (index) {
    if (!this.stepsEl) return;

    var q = QUESTIONS[index];
    if (!q) return;

    var step = document.createElement('div');
    step.className = 'mae__step';
    step.setAttribute('data-mae-step', String(index));
    step.setAttribute('role', 'group');
    step.setAttribute('aria-labelledby', 'mae-q-' + q.id);

    var html =
      '<span class="mae__step-label">' + escapeHtml(q.label) + '</span>' +
      '<h2 class="mae__step-question" id="mae-q-' + q.id + '">' + escapeHtml(q.question) + '</h2>' +
      '<ul class="mae__options" role="list">';

    q.options.forEach(function (opt) {
      html +=
        '<li class="mae__option" role="listitem">' +
        '<button type="button" class="mae__option-btn" data-value="' + escapeAttr(opt.value) + '">' +
        escapeHtml(opt.label) +
        '</button></li>';
    });

    html += '</ul>';
    step.innerHTML = html;

    this.stepsEl.innerHTML = '';
    this.stepsEl.appendChild(step);

    var self = this;
    step.querySelectorAll('.mae__option-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        self.onAnswer(q.id, btn.getAttribute('data-value'), btn);
      });
    });
  };

  /**
   * @param {string} questionId
   * @param {string} value
   * @param {HTMLButtonElement} btn
   */
  MortgageApprovalEstimator.prototype.onAnswer = function (questionId, value, btn) {
    this.answers[questionId] = value;

    var step = this.stepsEl.querySelector('[data-mae-step]');
    if (step) {
      step.querySelectorAll('.mae__option-btn').forEach(function (b) {
        b.classList.remove('is-selected');
        b.disabled = true;
      });
    }
    btn.classList.add('is-selected');

    this.score = calculateScore(this.answers);
    this.updateGauge(this.score, true);

    var self = this;
    window.setTimeout(function () {
      self.currentStep += 1;
      if (self.currentStep >= QUESTIONS.length) {
        self.showFinal();
      } else {
        self.renderStep(self.currentStep);
        self.updateProgress();
      }
    }, 380);
  };

  MortgageApprovalEstimator.prototype.updateProgress = function () {
    if (!this.progressBar) return;
    var pct = (this.currentStep / QUESTIONS.length) * 100;
    this.progressBar.style.width = pct + '%';
  };

  /**
   * @param {number} score
   * @param {boolean} animate
   */
  MortgageApprovalEstimator.prototype.updateGauge = function (score, animate) {
    var offset = GAUGE_CIRCUMFERENCE * (1 - score / 100);
    var display = Math.round(score);

    this.gaugeFills.forEach(function (fill) {
      fill.style.strokeDasharray = String(GAUGE_CIRCUMFERENCE);
      fill.style.strokeDashoffset = String(offset);
    });

    this.gaugeValues.forEach(function (el) {
      el.textContent = String(display);
    });

    this.root.querySelectorAll('.mae__gauge').forEach(function (gauge) {
      gauge.classList.toggle('is-updating', animate);
      window.setTimeout(function () {
        gauge.classList.remove('is-updating');
      }, animate ? 500 : 0);
    });

    if (this.finalScoreEl) {
      this.finalScoreEl.textContent = display + '%';
    }
  };

  MortgageApprovalEstimator.prototype.showFinal = function () {
    if (this.stepsEl) {
      this.stepsEl.innerHTML = '';
    }
    if (this.progressBar) {
      this.progressBar.style.width = '100%';
    }
    if (this.finalEl) {
      this.finalEl.hidden = false;
    }
    this.updateGauge(this.score, true);
  };

  function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  function escapeAttr(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/</g, '&lt;');
  }

  function boot() {
    document.querySelectorAll('[data-mae-widget]').forEach(function (el) {
      if (el._maeInstance) return;
      el._maeInstance = new MortgageApprovalEstimator(el);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
