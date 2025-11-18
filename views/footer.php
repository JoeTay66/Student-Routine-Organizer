</div> <!-- End dashboard-main -->
    </div> <!-- End dashboard-layout -->

    <!-- Dashboard Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dashboard functionality
            initializeDashboard();
            
            // Initialize mobile menu toggle
            initializeMobileMenu();
            
            // Initialize tool cards interactions
            initializeToolCards();
        });

        // Dashboard initialization
        function initializeDashboard() {
            // Add smooth transitions to all elements
            const elements = document.querySelectorAll('.tool-card, .activity-item, .quick-action-btn');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
                el.classList.add('fade-in-up');
            });
        }

        // Mobile menu toggle
        function initializeMobileMenu() {
            // Create mobile menu toggle button for smaller screens
            if (window.innerWidth <= 1024) {
                const header = document.querySelector('.dashboard-header');
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'mobile-menu-toggle';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.style.cssText = `
                    background: none;
                    border: none;
                    font-size: 1.25rem;
                    color: #64748b;
                    padding: 0.5rem;
                    border-radius: 0.5rem;
                    cursor: pointer;
                    margin-right: 1rem;
                `;
                
                header.insertBefore(toggleBtn, header.firstChild);
                
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.dashboard-sidebar');
                    const isOpen = sidebar.style.transform === 'translateX(0px)';
                    
                    if (isOpen) {
                        sidebar.style.transform = 'translateX(-100%)';
                        this.innerHTML = '<i class="fas fa-bars"></i>';
                    } else {
                        sidebar.style.transform = 'translateX(0px)';
                        this.innerHTML = '<i class="fas fa-times"></i>';
                    }
                });
            }
        }


        // Tool cards interactions
        function initializeToolCards() {
            const toolCards = document.querySelectorAll('.tool-card');
            
            toolCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.08)';
                });
            });

            // Quick action buttons
            const quickActionBtns = document.querySelectorAll('.quick-action-btn');
            quickActionBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Add ripple effect
                    createRipple(e, this);
                    
                    // Show feedback
                    const action = this.querySelector('span').textContent;
                    showToast(`Opening ${action}...`, 'success');
                });
            });
        }

        // Utility Functions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function animateNumber(element, start, end, duration) {
            const startTime = performance.now();
            const range = end - start;
            
            function updateNumber(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const current = Math.floor(start + (range * easeOutCubic(progress)));
                
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(updateNumber);
                } else {
                    element.textContent = end;
                }
            }
            
            requestAnimationFrame(updateNumber);
        }

        function easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 2rem;
                right: 2rem;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                border-radius: 0.5rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                max-width: 300px;
            `;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after delay
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        function createRipple(event, element) {
            const circle = document.createElement('span');
            const diameter = Math.max(element.clientWidth, element.clientHeight);
            const radius = diameter / 2;
            
            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - element.offsetLeft - radius}px`;
            circle.style.top = `${event.clientY - element.offsetTop - radius}px`;
            circle.style.position = 'absolute';
            circle.style.borderRadius = '50%';
            circle.style.background = 'rgba(255, 255, 255, 0.3)';
            circle.style.transform = 'scale(0)';
            circle.style.animation = 'ripple 0.6s linear';
            circle.style.pointerEvents = 'none';
            
            const ripple = element.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }
            
            circle.classList.add('ripple');
            element.appendChild(circle);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            .fade-in-up {
                animation: fadeInUp 0.6s ease-out forwards;
                opacity: 0;
                transform: translateY(20px);
            }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            /* Dashboard Grid Styles */
            .dashboard-grid {
                display: grid;
                grid-template-columns: 2fr 1fr;
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .dashboard-section {
                background: white;
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                border: 1px solid #e2e8f0;
                transition: all 0.2s ease;
            }

            .dashboard-section:hover {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            }

            .section-title {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 1.25rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 1.5rem;
                padding-bottom: 0.75rem;
                border-bottom: 2px solid #f1f5f9;
            }

            .section-title i {
                color: #667eea;
                font-size: 1.1rem;
            }

            /* Tools Grid */
            .tools-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .tool-card {
                background: white;
                border-radius: 1rem;
                padding: 1.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                border: 1px solid #e2e8f0;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .tool-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #667eea, #764ba2);
            }

            .tool-card.exercise-card::before { background: linear-gradient(90deg, #ef4444, #f97316); }
            .tool-card.diary-card::before { background: linear-gradient(90deg, #8b5cf6, #a855f7); }
            .tool-card.money-card::before { background: linear-gradient(90deg, #f59e0b, #eab308); }
            .tool-card.habit-card::before { background: linear-gradient(90deg, #10b981, #059669); }

            .tool-header {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1rem;
            }

            .tool-icon {
                width: 48px;
                height: 48px;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                color: white;
            }

            .tool-card.exercise-card .tool-icon { background: linear-gradient(135deg, #ef4444, #f97316); }
            .tool-card.diary-card .tool-icon { background: linear-gradient(135deg, #8b5cf6, #a855f7); }
            .tool-card.money-card .tool-icon { background: linear-gradient(135deg, #f59e0b, #eab308); }
            .tool-card.habit-card .tool-icon { background: linear-gradient(135deg, #10b981, #059669); }

            .tool-info h3 {
                margin: 0 0 0.25rem 0;
                font-size: 1.1rem;
                font-weight: 600;
                color: #1e293b;
            }

            .tool-info p {
                margin: 0;
                font-size: 0.875rem;
                color: #64748b;
            }

            .tool-stats {
                margin: 1rem 0;
            }

            .tool-stat {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1e293b;
            }

            .stat-text {
                font-size: 0.75rem;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .tool-actions {
                margin-top: 1rem;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                border: none;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }

            .btn-primary {
                background: #667eea;
                color: white;
            }

            .btn-primary:hover {
                background: #5a67d8;
                transform: translateY(-1px);
            }

            .btn-secondary {
                background: #f1f5f9;
                color: #64748b;
                border: 1px solid #e2e8f0;
            }

            .btn-secondary:hover {
                background: #e2e8f0;
                color: #475569;
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }

            .coming-soon {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: rgba(251, 191, 36, 0.1);
                color: #d97706;
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                border-radius: 0.375rem;
                font-weight: 600;
                border: 1px solid rgba(251, 191, 36, 0.2);
            }

            /* Activity Section */
            .activity-section {
                grid-row: span 2;
            }

            .activity-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .activity-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                background: #f8fafc;
                border-radius: 0.75rem;
                border: 1px solid #e2e8f0;
                transition: all 0.2s ease;
            }

            .activity-item:hover {
                background: #f1f5f9;
                border-color: #cbd5e1;
            }

            .activity-icon {
                width: 40px;
                height: 40px;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                color: white;
                flex-shrink: 0;
            }

            .activity-icon.exercise { background: #ef4444; }
            .activity-icon.diary { background: #8b5cf6; }
            .activity-icon.money { background: #f59e0b; }
            .activity-icon.habit { background: #10b981; }

            .activity-content {
                flex: 1;
            }

            .activity-text {
                margin: 0 0 0.25rem 0;
                font-size: 0.875rem;
                color: #374151;
                font-weight: 500;
            }

            .activity-time {
                font-size: 0.75rem;
                color: #6b7280;
            }

            /* Quick Actions */
            .quick-actions {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .quick-action-btn {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
                padding: 1.5rem 1rem;
                background: white;
                border: 2px solid #e2e8f0;
                border-radius: 0.75rem;
                text-decoration: none;
                color: #64748b;
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            }

            .quick-action-btn:hover {
                border-color: #667eea;
                color: #667eea;
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
            }

            .quick-action-btn i {
                font-size: 1.5rem;
            }

            .quick-action-btn span {
                font-size: 0.875rem;
                font-weight: 600;
            }

            .quick-action-btn.exercise:hover { border-color: #ef4444; color: #ef4444; }
            .quick-action-btn.diary:hover { border-color: #8b5cf6; color: #8b5cf6; }
            .quick-action-btn.money:hover { border-color: #f59e0b; color: #f59e0b; }
            .quick-action-btn.habit:hover { border-color: #10b981; color: #10b981; }

            /* Progress Section */
            .progress-cards {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .progress-card {
                padding: 1.5rem;
                background: #f8fafc;
                border-radius: 0.75rem;
                border: 1px solid #e2e8f0;
            }

            .progress-header {
                display: flex;
                justify-content: between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .progress-title {
                font-weight: 600;
                color: #374151;
            }

            .progress-trend {
                font-size: 0.875rem;
                font-weight: 600;
                padding: 0.25rem 0.5rem;
                border-radius: 0.375rem;
            }

            .progress-trend.up {
                background: #dcfce7;
                color: #16a34a;
            }

            .progress-trend.neutral {
                background: #fef3c7;
                color: #d97706;
            }

            .progress-stats {
                display: flex;
                gap: 2rem;
            }

            .progress-stat {
                display: flex;
                flex-direction: column;
            }

            .progress-number {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1e293b;
            }

            .progress-label {
                font-size: 0.75rem;
                color: #64748b;
            }

            .progress-message {
                margin-top: 0.5rem;
            }

            .progress-message p {
                margin: 0;
                font-size: 0.875rem;
                color: #6b7280;
                font-style: italic;
            }

            /* Motivation Section */
            .motivation-section {
                margin-top: 2rem;
            }

            .motivation-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2rem;
                border-radius: 1rem;
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .motivation-card::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(10deg); }
            }

            .motivation-icon {
                font-size: 2rem;
                margin-bottom: 1rem;
                opacity: 0.8;
            }

            .motivation-quote {
                font-size: 1.25rem;
                font-style: italic;
                margin: 0 0 1rem 0;
                position: relative;
                z-index: 2;
            }

            .motivation-author {
                font-size: 0.875rem;
                opacity: 0.9;
                position: relative;
                z-index: 2;
            }

            /* Responsive Design */
            @media (max-width: 1200px) {
                .dashboard-grid {
                    grid-template-columns: 1fr;
                }
                
                .activity-section {
                    grid-row: span 1;
                }
            }

            @media (max-width: 768px) {
                .tools-grid {
                    grid-template-columns: 1fr;
                }
                
                .quick-stats {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                .quick-actions {
                    grid-template-columns: 1fr;
                }
                
                .progress-stats {
                    flex-direction: column;
                    gap: 1rem;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>