document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.step-line').forEach((line) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    line.style.width = `${line.dataset.progress}%`;
                }
            });
        }, { threshold: 0.5 });

        observer.observe(line);
    });

    document.querySelectorAll('.study-card, .method-card').forEach((card) => {
        card.addEventListener('click', () => {
            const group = card.classList.contains('study-card') ? '.study-card' : '.method-card';

            document.querySelectorAll(group).forEach((item) => {
                item.classList.remove('border-[#33B6FF]', 'ring-2', 'ring-[#33B6FF]');
            });

            card.classList.add('border-[#33B6FF]', 'ring-2', 'ring-[#33B6FF]');
        });
    });
});