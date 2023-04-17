![logo](assets/aps.png)
# APS Segment HTTP API v1.0 
## Overview
This document describes how the segment http api drupal module passes data to the segment http api for tracking with discovr analytics.
It tracks the data of, registration, Login, logout, all Webform types, all Comment form types and Direct Contact messages

Further reference about the API is available here:
https://segment.com/docs/connections/sources/catalog/libraries/server/http-api/

API Spec:
https://segment.com/docs/connections/spec/identify/

Call types used: Identify, Track,

## API Calls index  
[Registration](##Registration)  
[Login](##Login)  
[Logout](##Logout)  
[FormAPI](##FormAPI)  
[CommentForm](##CommentForm)  
[DirectContact](##DirectContact)  
[WebformModule](##WebformModule)  

# Usage

## API Keys and Base Endpoint
These are configured from the administrative back-end, there will be a configuration page at /admin/config/system/segment-analytics. The site name should be entered using it's uppercase name, such as ABC-12345. 

The remaining fields should be pre-populated from the default values of the module, however, if the are not, they should be:

API Authorization - Basic aW5mb0BkaXNjb3ZyYW5hbHl0aWNzLmNvOlRoaW5rQXBzRGV2MTIzIQ==
Write Key - hZAoGDuiSlG4iGtLOPk2MHBK3YIAmdqr
Endpoint - https://api.segment.io/v1/

## Tracking Activity
By default this module performs 3 sets of actions, Live pinging, Activity tracking, and On-demand video tracking.

Live pinging - This sends a ping every 45 seconds to the analytics server, which is then counted on the Discovr platform to give live activity. This live data is not traceable to individual users in real time, and only post-event analytics can provide which specific users watched each content.

Activity tracking - Such as opening pages, clicking on links, changing tabs etc, are all dealt with by javascript, and will identify each user as they enter each page, and specific activities they do. As an update for v1.1.0, it now tracks users who register their interest on each Event. 

On-demand video tracking - Currently, this only works with Vimeo videos, and is only intended to work with a single embedded video. If there are multiple videos embedded on the page, this requires testing and potentially additional development. If the videos use a pop-up (lightbox) this must be Magnific Pop-up, as this is the only pop-up library that is currently developed to work with the tracking. 

# Development

## Segment API Calls
Here are all the call types used on the segment api by the aps segment http api module.

## Identify
identify lets you tie a user to their actions and record traits about them. It includes a unique User ID and any optional traits you know about them.
We recommend calling identify a single time when the user’s account is first created, and only identifying again later when their traits change.

> Example identify call:
```
POST https://api.segment.io/v1/identify
```
```
{
  "userId": "019mr8mf4r",
  "traits": {
    "email": "pgibbons@example.com",
    "name": "Peter Gibbons",
    "industry": "Technology"
  },
  "context": {
    "ip": "24.5.68.47"
  },
  "timestamp": "2012-12-02T00:30:08.276Z"
}
```
## Track
track lets you record the actions your users perform.Every action triggers what we call an “event”, which can also have associated properties.

You’ll want to track events that are indicators of success for your site, like Signed Up, Item Purchased or Article Bookmarked.

To get started, we recommend tracking just a few important events. You can always add more later!

> Example track call: 
```
POST https://api.segment.io/v1/track
```
```
{
  "userId": "019mr8mf4r",
  "event": "Item Purchased",
  "properties": {
    "name": "Leap to Conclusions Mat",
    "revenue": 14.99
  },
  "context": {
    "ip": "24.5.68.47"
  },
  "timestamp": "2012-12-02T00:30:12.984Z"
}
```

# Module Calls
## Registration

```
POST https://api.segment.io/v1/identify
``` 
type: identify  
event: registration  
data  

## Login 
```
POST https://api.segment.io/v1/identify
```
type: identify  
event: login  
data  

## Logout
```
POST https://api.segment.io/v1/identify
```
type: identify  
event: logout  
data  

## FormAPI
```
POST https://api.segment.io/v1/identify
```
type  
event  
data  

## CommentForm 
```
POST https://api.segment.io/v1/identify
```
type  
event  
data  

## DirectContact 
```
POST https://api.segment.io/v1/identify
```
type  
event  
data  

## WebformModule  
To send data to segment from webforms module you will need to add the aps segment webforms module which adds a custom webforms handler to capture the data.

```
POST https://api.segment.io/v1/identify
```
type  
event  
data  