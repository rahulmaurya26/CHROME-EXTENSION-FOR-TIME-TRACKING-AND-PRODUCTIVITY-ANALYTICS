// Get references to the buttons and the usage table
const liveBtn = document.getElementById('liveBtn');
const weeklyBtn = document.getElementById('weeklyBtn');
const table = document.getElementById('usageTable');

// Add click event listener for the "Today" (live) button
liveBtn.addEventListener('click', () => {
  setActiveButton(liveBtn);   // Highlight the clicked button
  loadUsage('live');          // Load today's usage data
});

// Add click event listener for the "Weekly" button
weeklyBtn.addEventListener('click', () => {
  setActiveButton(weeklyBtn); // Highlight the clicked button
  loadUsage('weekly');        // Load weekly usage data
});

// Function to visually mark the active button and reset others
function setActiveButton(activeBtn) {
  [liveBtn, weeklyBtn].forEach(btn => {
    btn.classList.remove('active'); // Remove active class from both buttons
  });
  activeBtn.classList.add('active'); // Add active class to the clicked button
}

// Function to clear all rows from the table except the header row
function clearTable() {
  while (table.rows.length > 1) {
    table.deleteRow(1); // Delete row at index 1 repeatedly (after header)
  }
}

// Function to fetch usage data from the server and populate the table
function loadUsage(type) {
  clearTable(); // Clear existing data from the table

  // Build URL with query parameter to specify data type ('live' or 'weekly')
  const url = `http://localhost/web_time_tracker/fetch_data.php?type=${type}`;

  // Fetch data from the server
  fetch(url)
    .then(res => res.json()) // Parse response as JSON
    .then(data => {
      if (data.length === 0) {
        // If no data returned, show a "No data found" message spanning all columns
        const row = table.insertRow();
        row.innerHTML = `<td colspan="3" style="text-align:center;">No data found</td>`;
        return;
      }
      // For each data item, add a new row with site, total time, and category
      data.forEach(item => {
        const row = table.insertRow();
        row.innerHTML = `<td>${item.site}</td><td>${item.total_time}</td><td>${item.category}</td>`;
      });
    })
    .catch(err => {
      // On error (e.g., network issue), show an error message in the table
      const row = table.insertRow();
      row.innerHTML = `<td colspan="3" style="text-align:center; color:red;">Error loading data</td>`;
    });
}

// Load "Today" usage data on initial load of the popup
loadUsage('live');
