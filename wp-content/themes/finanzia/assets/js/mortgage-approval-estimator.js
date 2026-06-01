/**
 * Mortgage Approval Estimator — vanilla JS, no dependencies.
 * One question at a time; updates SVG gauge and score after each answer.
 */
(function () {
  'use strict';

  var CONFIG = window.finaramMaeConfig || {};
  var BASE_SCORE = toNum(CONFIG.baseScore, 50);
  var MIN_SCORE = toNum(CONFIG.minScore, 0);
  var MAX_SCORE = toNum(CONFIG.maxScore, 97);

  var GAUGE_CIRCUMFERENCE = 2 * Math.PI * 88;
  var STEP_COUNT = 5;

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

  function toNum(value, fallback) {
    var n = Number(value);
    return isFinite(n) ? n : fallback;
  }

  function clampScore(score) {
    var n = toNum(score, BASE_SCORE);
    return Math.max(MIN_SCORE, Math.min(MAX_SCORE, n));
  }

  /**
   * Raw score from answers (before clamp).
   * @param {Record<string, string>} answers
   * @returns {number}
   */
  function calculateRawScore(answers) {
    var score = BASE_SCORE;

    switch (answers.ltv) {
      case 'ltv_80':
        score += 12;
        break;
      case 'ltv_80_90':
        score += 6;
        break;
      case 'ltv_90':
        if (answers.age === 'age_36_plus') {
          score -= 15;
        }
        break;
      default:
        break;
    }

    if (answers.age === 'age_under_36') {
      score += 10;
    }

    switch (answers.citizenship) {
      case 'citizenship_cz':
        score += 10;
        break;
      case 'citizenship_eu':
        score += 8;
        break;
      case 'citizenship_non_eu':
        break;
      case 'citizenship_high_risk':
        score -= 15;
        break;
      default:
        break;
    }

    switch (answers.residency) {
      case 'residency_cz_citizen':
      case 'residency_permanent':
        score += 12;
        break;
      case 'residency_long_term':
        score += 9;
        break;
      case 'residency_temporary':
        score -= 5;
        break;
      case 'residency_none':
        score -= 15;
        break;
      default:
        break;
    }

    switch (answers.income) {
      case 'income_cz_employment':
        score += 12;
        break;
      case 'income_osvc':
        score += 8;
        break;
      case 'income_company_owner':
        score += 6;
        break;
      case 'income_rental':
        score += 6;
        break;
      case 'income_eu_salary':
        score += 4;
        break;
      case 'income_non_eu_salary':
        score -= 10;
        break;
      case 'income_business_abroad':
        score -= 14;
        break;
      default:
        break;
    }

    return score;
  }

  function calculateScore(answers) {
    return clampScore(calculateRawScore(answers));
  }

  function MortgageApprovalEstimator(root) {
    this.root = root;
    this.answers = {};
    this.currentStep = 0;
    this.score = BASE_SCORE;
    this.isComplete = false;
    this.advanceTimer = null;

    this.stepsEl = root.querySelector('[data-mae-steps]');
    this.finalEl = root.querySelector('[data-mae-final]');
    this.progressBar = root.querySelector('[data-mae-progress-bar]');
    this.finalScoreEl = root.querySelector('[data-mae-final-score]');
    this.questionnaireEl = root.querySelector('[data-mae-questionnaire]');

    this.gaugeFills = root.querySelectorAll('[data-mae-gauge-fill]');
    this.gaugeValues = root.querySelectorAll('[data-mae-gauge-value]');

    this.init();
  }

  MortgageApprovalEstimator.prototype.init = function () {
    this.initGaugeStroke();
    this.renderStep(0);
    this.recalculateAndUpdate(true);
    this.updateProgress();
  };

  MortgageApprovalEstimator.prototype.initGaugeStroke = function () {
    var offset = GAUGE_CIRCUMFERENCE * (1 - BASE_SCORE / 100);
    this.gaugeFills.forEach(function (fill) {
      fill.style.strokeDasharray = String(GAUGE_CIRCUMFERENCE);
      fill.style.strokeDashoffset = String(offset);
    });
  };

  MortgageApprovalEstimator.prototype.clearAdvanceTimer = function () {
    if (this.advanceTimer) {
      window.clearTimeout(this.advanceTimer);
      this.advanceTimer = null;
    }
  };

  MortgageApprovalEstimator.prototype.hideFinal = function () {
    this.isComplete = false;
    if (this.finalEl) {
      this.finalEl.hidden = true;
    }
    if (this.stepsEl) {
      this.stepsEl.hidden = false;
    }
  };

  MortgageApprovalEstimator.prototype.renderStep = function (index) {
    if (!this.stepsEl) return;

    var q = QUESTIONS[index];
    if (!q) return;

    var savedValue = this.answers[q.id] || '';
    var showBack = index > 0;

    var step = document.createElement('div');
    step.className = 'mae__step';
    step.setAttribute('data-mae-step', String(index));
    step.setAttribute('role', 'group');
    step.setAttribute('aria-labelledby', 'mae-q-' + q.id);

    var html =
      '<div class="mae__step-nav">' +
      (showBack
        ? '<button type="button" class="mae__back" data-mae-back>&larr; Back</button>'
        : '<span class="mae__back-spacer" aria-hidden="true"></span>') +
      '</div>' +
      '<span class="mae__step-label">' + escapeHtml(q.label) + '</span>' +
      '<h2 class="mae__step-question" id="mae-q-' + q.id + '">' + escapeHtml(q.question) + '</h2>' +
      '<ul class="mae__options" role="list">';

    q.options.forEach(function (opt) {
      var selected = savedValue === opt.value ? ' is-selected' : '';
      html +=
        '<li class="mae__option" role="listitem">' +
        '<button type="button" class="mae__option-btn' + selected + '" data-value="' + escapeAttr(opt.value) + '">' +
        escapeHtml(opt.label) +
        '</button></li>';
    });

    html += '</ul>';
    step.innerHTML = html;

    this.stepsEl.innerHTML = '';
    this.stepsEl.appendChild(step);
    this.stepsEl.hidden = false;

    var self = this;
    var backBtn = step.querySelector('[data-mae-back]');
    if (backBtn) {
      backBtn.addEventListener('click', function () {
        self.goBack();
      });
    }

    step.querySelectorAll('.mae__option-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        self.onAnswer(q.id, btn.getAttribute('data-value'), btn);
      });
    });
  };

  MortgageApprovalEstimator.prototype.goBack = function () {
    this.clearAdvanceTimer();
    this.hideFinal();

    if (this.currentStep <= 0) {
      return;
    }

    for (var i = this.currentStep; i < QUESTIONS.length; i++) {
      delete this.answers[QUESTIONS[i].id];
    }

    this.currentStep -= 1;
    this.renderStep(this.currentStep);
    this.recalculateAndUpdate(true);
    this.updateProgress();
  };

  MortgageApprovalEstimator.prototype.onAnswer = function (questionId, value, btn) {
    if (!value) return;

    this.clearAdvanceTimer();
    this.hideFinal();
    this.answers[questionId] = value;

    var step = this.stepsEl.querySelector('[data-mae-step]');
    if (step) {
      step.querySelectorAll('.mae__option-btn').forEach(function (b) {
        b.classList.remove('is-selected');
        b.disabled = true;
      });
    }
    btn.classList.add('is-selected');

    this.recalculateAndUpdate(true);

    var self = this;
    this.advanceTimer = window.setTimeout(function () {
      self.advanceTimer = null;
      if (self.currentStep < QUESTIONS.length - 1) {
        self.currentStep += 1;
        self.renderStep(self.currentStep);
        self.updateProgress();
      } else {
        self.showFinal();
      }
    }, 320);
  };

  MortgageApprovalEstimator.prototype.recalculateAndUpdate = function (animate) {
    this.score = calculateScore(this.answers);
    this.updateGauge(this.score, animate);
  };

  MortgageApprovalEstimator.prototype.countAnswered = function () {
    var n = 0;
    for (var i = 0; i < QUESTIONS.length; i++) {
      if (this.answers[QUESTIONS[i].id]) n += 1;
    }
    return n;
  };

  MortgageApprovalEstimator.prototype.updateProgress = function () {
    if (!this.progressBar) return;
    var answered = this.isComplete ? STEP_COUNT : this.countAnswered();
    var pct = (answered / STEP_COUNT) * 100;
    this.progressBar.style.width = pct + '%';
  };

  MortgageApprovalEstimator.prototype.updateGauge = function (score, animate) {
    var safeScore = clampScore(score);
    var offset = GAUGE_CIRCUMFERENCE * (1 - safeScore / 100);
    var display = Math.round(safeScore);

    this.gaugeFills.forEach(function (fill) {
      fill.style.strokeDasharray = String(GAUGE_CIRCUMFERENCE);
      fill.style.strokeDashoffset = String(offset);
    });

    this.gaugeValues.forEach(function (el) {
      el.textContent = String(display);
    });

    this.root.querySelectorAll('.mae__gauge').forEach(function (gauge) {
      gauge.classList.toggle('is-updating', !!animate);
      if (animate) {
        window.setTimeout(function () {
          gauge.classList.remove('is-updating');
        }, 450);
      }
    });

    if (this.finalScoreEl) {
      this.finalScoreEl.textContent = display + '%';
    }
  };

  MortgageApprovalEstimator.prototype.showFinal = function () {
    this.isComplete = true;
    this.clearAdvanceTimer();

    if (this.stepsEl) {
      this.stepsEl.innerHTML =
        '<div class="mae__step-nav">' +
        '<button type="button" class="mae__back" data-mae-back-from-final>&larr; Back</button>' +
        '</div>';
      this.stepsEl.hidden = false;

      var self = this;
      var backBtn = this.stepsEl.querySelector('[data-mae-back-from-final]');
      if (backBtn) {
        backBtn.addEventListener('click', function () {
          self.isComplete = false;
          self.currentStep = QUESTIONS.length - 1;
          self.hideFinal();
          self.renderStep(self.currentStep);
          self.recalculateAndUpdate(false);
          self.updateProgress();
        });
      }
    }

    if (this.progressBar) {
      this.progressBar.style.width = '100%';
    }
    if (this.finalEl) {
      this.finalEl.hidden = false;
    }
    this.recalculateAndUpdate(true);
    this.updateProgress();
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
