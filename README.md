# CHROME-EXTENSION-FOR-TIME-TRACKING-AND-PRODUCTIVITY-ANALYTICS

  COMPANY : CODTECH IT SOLUTIONS PVT. LTD.

  NAME : RAHUL MAURYA

  INTERN ID : CT08DA939

  DOMAIN : FULL STACK WEB DEVELOPMENT

  DURATION : 8 WEEKS

  MENTOR : NEELA SANTHOSH KUMAR

 # Task Description:CHROME-EXTENSION-FOR-TIME-TRACKING-AND-PRODUCTIVITY-ANALYTICS

  ## Web Time Tracker
       Web Time Tracker is a Chrome extension project designed to monitor the amount of time a user spends on various websites. The goal is to help users understand their web usage patterns by collecting and 
       displaying time-based analytics in an organized and categorized way. The system includes both frontend and backend components, working together to track, store, and display usage data.
  ## Extension Functionality (Frontend - JavaScript + HTML)
       The extension runs in the browser and automatically tracks time spent on each site. It does this by listening for two main browser events:
       
       when the active tab changes (onActivated) and when a tab finishes loading (onUpdated).
       
       Each time a user switches tabs or navigates within a tab, the script calculates the time spent on the previous tab by subtracting the start time from the current time.

       The main tracking logic is in the background.js file. This file keeps track of the current site, tab ID, and start time. 
       
       When a change is detected, it calculates the time spent and sends this data to a server using a POST request.

       The user interface is a popup that opens when the extension icon is clicked. The UI is built using HTML and styled with CSS for simplicity and clarity. 
       
       The popup displays a table of usage data with three columns: 
       
       site, time spent, and category. It includes two buttons—Today and Weekly—to toggle between current-day and 7-day summaries. 
       
       The popup.js file manages this logic, sending a GET request to the backend  to fetch and display relevant data.
       
  ## Backend Logic (PHP + MySQL)
      The backend is written in PHP and uses MySQL for data storage. When the extension sends time data, log_time.php receives it via a POST request. 
      
      This script validates the data, connects to the database, and inserts the record into the usage_data table, which contains columns for the site, duration, and timestamp.

      The second backend script, fetch_data.php, is responsible for serving usage data to the popup.
      
      It accepts a query parameter (type) which determines whether to return today’s data (live) or the past 7 days  (weekly). 
      
      The script groups and sums durations by site, then returns the data as JSON.

      To enhance the usefulness of the report, the backend also classifies each site as Productive, Unproductive, or Neutral using a predefined list of domains.
      
      For example, sites like GitHub or Stack Overflow are considered productive, while Facebook or YouTube are marked unproductive.  
      
  ## Data Flow and Integration
     The flow of data is seamless: user behavior is captured in the browser, sent to the backend server, stored in a database, and finally fetched by the UI to be presented to the user.
     
     Everything is done using local resources (localhost), ensuring that no external services are involved
     
  ## Conclusion
     This project is a simple but effective tool for web usage analytics. 
     
     It combines browser scripting, UI rendering, PHP-based API handling, and MySQL data storage in a full-stack setup.
     
     It’s ideal for productivity analysis, parental monitoring, or self-awareness of browsing habits. With small improvements, such as login authentication or category customization, this project can evolve into a professional-grade productivity tool.
