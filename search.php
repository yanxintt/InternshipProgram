<style>
    #search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px auto;
        width: 100%;
        max-width: 600px;
        cursor: pointer;
        font-size: 1.5rem;
        color: #007BFF;
    }

    #search-container input {
        padding: 10px;
        font-size: 1rem;
        width: 80%;
        max-width: 580px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 2px; 
        display: inline-block;
    }

    #search-container i {
        cursor: pointer;
        font-size: 1.5rem;
        color:rgb(122, 167, 215);
        margin-left: 10px;
    }

    </style>

    <script>
    // Function to search feedback based on search bar input
    function searchJobs() {
        const input = document.getElementById('search').value.toLowerCase();
        const rows = document.querySelectorAll('.requests-table tbody tr');
        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let found = false;
            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(input)) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? '' : 'none';
        });
    }
    </script>

<script>
    // Function to search feedback based on search bar input
    function searchFeedback() {
        const input = document.getElementById('search').value.toLowerCase();
        const rows = document.querySelectorAll('.requests-table tbody tr');
        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let found = false;
            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(input)) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? '' : 'none';
        });
    }
    </script>