What Songs Do We Know?
A web app using the facebook API/PHP SDK built for YHack 2015 by Connor Dube and Olivia Gunton

The purpose of this app is to allow attendees of a facebook event to see what knowledge they and other attendees have in common. Our use case is folk songs from the Republic of Georgia, but the app could be extended/repurposed for use with card games, programming skills, etc. The original idea came out of Olivia's experience as a singer of Georgian folk songs, finding herself at gatherings of musicians with no efficient way of determining a shared repertoire to sing/jam together.

Users authenticate through facebook and can see a list of events (currently, a narrowed-down list of public events; in future, we hope to pass Facebook's formal app review and use the user_events permission to make it a list of all events visible to that user). Each event is clickable and leads to a list of songs known to those attendees of the event who are also users of the app. The songs are sorted in descending order by how many people know each song, and songs that the current user knows are highlighted. On a separate page, the current user can view and add to his/her list of songs s/he knows.

The app is currently running on a local XAMPP web server and MySQL database instance, but could be deployed in its current form to an external host.