  // Animation des particules (optionnel)
                document.addEventListener('DOMContentLoaded', function() {
                    const formContainer = document.querySelector('.form-container');
                    
                    function createParticle() {
                        const particle = document.createElement('div');
                        particle.classList.add('particle');
                        
                        const size = Math.random() * 5 + 2;
                        particle.style.width = `${size}px`;
                        particle.style.height = `${size}px`;
                        particle.style.left = `${Math.random() * 100}%`;
                        particle.style.top = '0';
                        particle.style.opacity = Math.random() * 0.5 + 0.2;
                        
                        formContainer.appendChild(particle);
                        
                        const duration = Math.random() * 3000 + 2000;
                        const animation = particle.animate([
                            { transform: 'translateY(0)', opacity: particle.style.opacity },
                            { transform: `translateY(${formContainer.offsetHeight}px)`, opacity: 0 }
                        ], { duration, easing: 'linear' });
                        
                        animation.onfinish = () => particle.remove();
                    }
                    
                    setInterval(createParticle, 150);
                });