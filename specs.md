# Web Authoring Final Project

## REVINE
Video Sharing Platform for users to upload and share 8 second videos.

### Table Schemas

Members Table
  - id (primary key)
  - is_admin (bool)
  - Username (varchar(255))
  - Email (varchar(255))
  - hashed_pswd (varchar(32))
  - member_since (DATE)

Videos Table
  - id (primary key)
  - user_id (foreign key)
  - title (varchar(255))
  - description (varchar(255))
  - video_path (varchar(255))
  - created_at (timestamp)
  - status (enum: 'uploaded', 'processing', 'ready', 'failed')
  - error_message (varchar(255))

