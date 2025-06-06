// Variables to keep track of the current tab, start time, and current site
let currentTabId = null;
let startTime = null;
let currentSite = null;

// Helper function to extract the hostname from a given URL
function getHostname(url) {
  try {
    return new URL(url).hostname;
  } catch {
    return null; // Return null if the URL is invalid
  }
}

// Function to send the duration spent on a site to the server
async function sendDuration(site, duration) {
  // Don't send if site is invalid or duration is zero/negative
  if (!site || duration <= 0) return;

  console.log(`Sending ${site} duration ${duration}s`);

  // Send POST request to the local server with the site and duration
  fetch("http://localhost/task/log_time.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `site=${encodeURIComponent(site)}&duration=${duration}`
  })
  .then(response => response.json()) // Parse the response as JSON
  .then(data => console.log("Response:", data)) // Log the server response
  .catch(err => console.error("Error sending data:", err)); // Handle errors
}

// Function to handle when the user changes tabs
async function handleTabChange(tabId) {
  const tab = await chrome.tabs.get(tabId); // Get tab information
  const hostname = getHostname(tab.url);    // Extract the hostname from the URL

  // If tracking was already active, calculate and send the time spent
  if (currentSite && startTime !== null) {
    const duration = Math.floor((Date.now() - startTime) / 1000); // in seconds
    await sendDuration(currentSite, duration); // Send the duration to the server
  }

  // Start tracking the new tab/site
  currentSite = hostname;
  currentTabId = tabId;
  startTime = Date.now();
}

// Listener: When the user activates (switches to) a different tab
chrome.tabs.onActivated.addListener(activeInfo => {
  handleTabChange(activeInfo.tabId);
});

// Listener: When a tab is updated (e.g. page is reloaded or navigated)
chrome.tabs.onUpdated.addListener((tabId, changeInfo, tab) => {
  // Only handle update when it's the active tab and loading is complete
  if (tab.active && changeInfo.status === 'complete') {
    handleTabChange(tabId);
  }
});

// On extension load: Start tracking the currently active tab
chrome.tabs.query({active: true, currentWindow: true}, (tabs) => {
  if (tabs.length) {
    handleTabChange(tabs[0].id);
  }
});
