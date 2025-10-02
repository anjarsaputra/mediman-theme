<?php if ($active_tab === 'aksi_cepat') : ?>
    <div class="action-cards-container">
        <?php foreach ($quick_links as $link) : ?>
            <a href="<?php echo esc_url($link['link']); ?>" class="action-card">
                <div class="action-card-icon">
                    <span class="dashicons <?php echo esc_attr($link['icon']); ?>"></span>
                </div>
                <div class="action-card-content">
                    <h3><?php echo esc_html($link['title']); ?></h3>
                    <p><?php echo esc_html($link['text']); ?></p>
                </div>
                <div class="action-card-arrow">
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <style>
    .action-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    .action-card {
        display: flex;
        align-items: center;
        background: white;
        padding: 25px;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        color: inherit;
    }

    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }

    .action-card-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: #f0f9ff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }

    .action-card-icon .dashicons {
        color: #3b82f6;
        font-size: 24px;
        width: 24px;
        height: 24px;
    }

    .action-card-content {
        flex: 1;
    }

    .action-card-content h3 {
        margin: 0 0 5px;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .action-card-content p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.4;
    }

    .action-card-arrow {
        flex-shrink: 0;
        margin-left: 20px;
        opacity: 0.3;
        transition: opacity 0.3s ease;
    }

    .action-card:hover .action-card-arrow {
        opacity: 1;
        transform: translateX(5px);
    }

    .action-card-arrow .dashicons {
        font-size: 20px;
        width: 20px;
        height: 20px;
    }

    /* Hover Effects */
    .action-card[href*="customize.php"] .action-card-icon {
        background: #ecfdf5;
    }
    
    .action-card[href*="customize.php"] .action-card-icon .dashicons {
        color: #059669;
    }
    
    .action-card[href*="nav-menus.php"] .action-card-icon {
        background: #eff6ff;
    }
    
    .action-card[href*="nav-menus.php"] .action-card-icon .dashicons {
        color: #3b82f6;
    }
    
    .action-card[href*="widgets.php"] .action-card-icon {
        background: #f3e8ff;
    }
    
    .action-card[href*="widgets.php"] .action-card-icon .dashicons {
        color: #7c3aed;
    }

    /* Responsive Design */
    @media screen and (max-width: 782px) {
        .action-cards-container {
            grid-template-columns: 1fr;
        }

        .action-card {
            padding: 20px;
        }

        .action-card-icon {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }

        .action-card-content h3 {
            font-size: 15px;
        }

        .action-card-content p {
            font-size: 13px;
        }
    }
    </style>
<?php endif; ?>