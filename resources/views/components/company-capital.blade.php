<div id="company-capital" class="d-flex align-items-center">
    <span class="me-2">Company Capital:</span>
    <span id="capital-amount" class="fs-4 fw-bold text-primary">{{ number_format($capital, 2) }}</span>
    <div class="d-flex flex-column justify-content-center align-items-center">
        <small class="mx-1">Change from the last week</small>
        <span id="capital-change" class="badge bg-success me-1 w-50">
            <i class="ri-arrow-up-line"></i> 0
        </span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function fetchCapital() {
            fetch('{{ route('company.capital') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('capital-amount').textContent = data.capital;
                    const capitalChangeElement = document.getElementById('capital-change');
                    capitalChangeElement.textContent = `${data.netChange} %`;
                    if (parseFloat(data.netChange) < 0) {
                        capitalChangeElement.classList.remove('bg-success');
                        capitalChangeElement.classList.add('bg-danger');
                        capitalChangeElement.innerHTML = `<i class="ri-arrow-down-line"></i> ${data.netChange} %`;
                    } else {
                        capitalChangeElement.classList.remove('bg-danger');
                        capitalChangeElement.classList.add('bg-success');
                        capitalChangeElement.innerHTML = `<i class="ri-arrow-up-line"></i> ${data.netChange} %`;
                    }
                })
                .catch(error => console.error('Error fetching capital:', error));
        }

        // Fetch capital every 10 seconds
        setInterval(fetchCapital, 10000);

        // Initial fetch
        fetchCapital();
    });
</script>