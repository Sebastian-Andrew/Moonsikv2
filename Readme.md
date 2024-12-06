Moonsik

Functionality:

1. Login/ Signup.
2. Search for favourite tracks, artists, albums etc.
3. Create and customize playlists.
4. Control the music with usual controls such as play, pause, skip, shuffle, repeat etc.
5. Adjust volume using volume bar.
6. Update user details / Logout.

Not included:
1. Administration because there's no point.
2. Can't add music, only for listeners.

Languages/Skills used:
1. HTML5
2. CSS3
3. JavaScript
4. PHP
5. MYSQL
6. AJAX
7. jQuery
8. JSON

if for some reason idk why the login won't work, use this on database
UPDATE users SET password = MD5('newpassword') WHERE username = 'change this to your account name typeshii';

for daniel:
how to add music manually.
basically go to PHPAdmin, go to Songs, click Insert on top of the site between Search and Export, after that you will be shown of multiple columns.
1. on the first column, "Id" your starting number is 33 because there's already music existing with previous numbers.
2. "title", pretty self explanatory. just pick a name for the music you downloaded.
3. "Artist", there is 6 artists, which is 1. Laus, 2. Daniel, 3. Sebastian, 4. Andrew, 5. Bruce Lee, 6. Ventura. Just pick whichever you want by typing the number on the third column.
4. "Album", you can make your own or just pick existing ones, same as Artists, pick between 1-7.
5. "Genre", pretty self explanatory again. Pick the number your song/music is best suited to.
[1.Rock 2.Pop 3.Hip-hop 4.Rap 5.R & B 6.Classical 7.Techno 8.Jazz 9.Folk 10.Country]
6. "Duration", type on how long is your song/music.
7. "Path", so you have to paste this "assets/music/putthenameofyourmusichere.mp3" there.
8. "albumOrder" on which order would you place your song in the album chose/made.
9. "plays" how many times the song/music is played.