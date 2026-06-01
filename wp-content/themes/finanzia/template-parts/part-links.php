<?php if (is_countable($args['sections_keys'])): ?>
    <ul class="mortgages__links">
        <?php foreach ($args['sections_keys'] as $sectionsKey) : ?>
            <?php switch ($sectionsKey):
                case "-top_block": ?>
                    <li class="active">
                        <a href="#top-sec"><?php _ex("Calculators", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "mortgage_calc": ?>
                    <li>
                        <a href="#calc-sec"><?php _ex("Calculators", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "loans_calc": ?>
                    <li>
                        <a href="#loan-calc-sec"><?php _ex("Calculators", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "partners": ?>
                    <li>
                        <a href="#loan-sec"><?php _ex("Loan of banks", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "how_work": ?>
                    <li>
                        <a href="#how-sec"><?php _ex("Application Process", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "about": ?>
                    <li>
                        <a href="#about-sec"><?php _ex("Financing", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "cost": ?>
                    <li>
                        <a href="#cost-sec"><?php _ex("Cost of Services", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "documents": ?>
                    <li>
                        <a href="#doc-sec"><?php _ex("Application Documents", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "faqs": ?>
                    <li>
                        <a href="#faq-sec"><?php _ex("FAQ", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "why": ?>
                    <li>
                        <a href="#why-sec"><?php _ex("Our Strengths", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "investment": ?>
                    <li>
                        <a href="#mortgage-invest-sec"><?php _ex("Mortgage Investment Calculator", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php case "feedback": ?>
                    <li>
                        <a href="#feedback-sec"><?php _ex("Consultation", 'part-links', 'finanzia'); ?></a>
                    </li>
                    <?php break; ?>
                <?php endswitch; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>