# Web Authoring Final Project

## REVINE
Video Sharing Platform for users to upload and share 8 second videos.

### Stack
- Apache web server
  - Serves frontend and backend.
  - Serves video files from the filesystem.
- FFMPEG
  - Used for video processing (e.g., transcoding, thumbnail generation).
- MariaDB database
  - Stores user and video metadata.
- PHP backend
  - Handles user authentication, video uploads, and video processing.
- HTML/CSS/JS frontend.
  - Provides user interface for registration, login, video upload, and video browsing.

### Features
- User Registration and Authentication
- Video Uploading
- Video Processing (transcoding and thumbnail generation)
- Adaptive Streaming (using HLS)
- Video Browsing and Playback
- Like/Dislike System
- Commenting System
- Tagging System (optional)

### Table Schemas

- Members Table
  - id (primary key)
  - is_admin (bool)
  - Username (varchar(255))
  - Email (varchar(255))
  - hashed_pswd (varchar(32))
  - member_since (DATE)

- Videos Table
  - id (primary key)
  - user_id (foreign key)
  - title (varchar(255))
  - description (varchar(255))
  - duration (int) *in seconds, rounded to nearest second*
  - video_path (varchar(255))
  - thumbnail_path (varchar(255))
  - created_at (timestamp)
  - status (enum: 'uploaded', 'processing', 'ready', 'failed')
  - error_message (varchar(255))

- Likes Table
  - id (primary key)
  - video_id (foreign key)
  - user_id (foreign key)

- Dislikes Table
  - id (primary key)
  - video_id (foreign key)
  - user_id (foreign key)

- Comments Table
  - id (primary key)
  - video_id (foreign key)
  - user_id (foreign key)
  - comment_text (varchar(255))
  - created_at (timestamp)
