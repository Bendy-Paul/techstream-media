# TechStream Media - Database Class Diagram

## Overview
This document presents a comprehensive class diagram derived from all database migrations in the TechStream Media application.

---

## Entity Relationship Diagram (Text Format)

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              CORE ENTITIES                                          │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│      USERS         │
├────────────────────┤
│ id (PK)            │
│ name               │
│ email (UNIQUE)     │
│ password           │
│ role               │
│ avatar_url         │
│ is_active          │
│ email_verified_at  │
│ remember_token     │
│ subscription_tier  │
│ timestamps         │
└────────────────────┘
         │
         │ 1:N
         ├─────────────────┬──────────────────┬──────────────────┐
         │                 │                  │                  │
    ┌────▼─────────┐  ┌───▼──────────┐  ┌────▼────────────┐  ┌──▼──────────────┐
    │  COMPANIES   │  │   ARTICLES   │  │    RESUMES      │  │   SAVED_ITEMS   │
    ├──────────────┤  ├──────────────┤  ├─────────────────┤  ├─────────────────┤
    │ id (PK)      │  │ id (PK)      │  │ id (PK)         │  │ id (PK)         │
    │ user_id (FK) │  │ author_id(FK)│  │ user_id (FK)    │  │ user_id (FK)    │
    │ name         │  │ title        │  │ title           │  │ item_id (Poly)  │
    │ slug (UNQ)   │  │ slug (UNQ)   │  │ summary         │  │ item_type       │
    │ tagline      │  │ content      │  │ is_default      │  │ timestamps      │
    │ description  │  │ featured_img │  │ visibility      │  └─────────────────┘
    │ logo_url     │  │ status       │  │ timestamps      │
    │ cover_image  │  │ views        │  │ index(user_id)  │
    │ email        │  │ is_featured  │  └─────────────────┘
    │ phone        │  │ published_at │            │
    │ website_url  │  │ timestamps   │            │ 1:N
    │ address      │  └──────────────┘            │
    │ city_id (FK) │       │                      │
    │ state_id     │       │ 1:N                  ├─────────────────┬──────────────────┐
    │ country_id   │       │                      │                 │                  │
    │ team_size    │       ├─────┬────┬─────┐    ├──────────────┬──┴──────────────┐
    │ year_founded │       │     │    │     │    │              │                 │
    │ starting_cost│       │     │    │     │    │              │                 │
    │ currency     │   ┌───▼──┐ │ ┌──┴──┐ │ ┌──▼──────────┐ ┌──▼──────────┐ ┌───▼────────────┐
    │ subscription │   │      │ │ │     │ │ │EXPERIENCES │ │ EDUCATION   │ │RESUME_SKILLS   │
    │ _tier        │   │      │ │ │     │ │ ├─────────────┤ ├─────────────┤ ├────────────────┤
    │ is_verified  │   │      │ │ │     │ │ │ id (PK)     │ │ id (PK)     │ │ id (PK)        │
    │ is_featured  │   │      │ │ │     │ │ │resume_id(FK)│ │resume_id(FK)│ │ resume_id(FK)  │
    │ social_links │   │      │ │ │     │ │ │company_name │ │institution  │ │ skills (JSON)  │
    │ profile_stats│   │      │ │ │     │ │ │job_title    │ │degree       │ │ timestamps     │
    │ status       │   │      │ │ │     │ │ │emp_type     │ │field_of_study│
    │ timestamps   │   │      │ │ │     │ │ │start_date   │ │start_date   │
    └──────────────┘   │      │ │ │     │ │ │end_date     │ │end_date     │
         │              │      │ │ │     │ │ │is_current   │ │grade        │
         │ 1:N          │      │ │ │     │ │ │description  │ │timestamps   │
         │              │      │ │ │     │ │ │timestamps   │ └─────────────┘
    ┌────▼──────────────────┐ │ │ │     │ │ └─────────────┘
    │   COMPANY_GALLERIES   │ │ │ │     │ │
    ├───────────────────────┤ │ │ │     │ │
    │ id (PK)               │ │ │ │     │ │
    │ company_id (FK)       │ │ │ │     │ │
    │ image_url             │ │ │ │     │ │
    │ caption               │ │ │ │     │ │
    └───────────────────────┘ │ │ │     │ │
                              │ │ │     │ │
    ┌──────────────────────┐  │ │ │     │ │
    │  COMPANY_LOCATIONS   │  │ │ │     │ │
    ├──────────────────────┤  │ │ │     │ │
    │ id (PK)              │  │ │ │     │ │
    │ company_id (FK)      │  │ │ │     │ │
    │ city_id (FK)         │  │ │ │     │ │
    │ address              │  │ │ │     │ │
    │ is_headquarters      │  │ │ │     │ │
    └──────────────────────┘  │ │ │     │ │
                              │ │ │     │ │
    ┌──────────────────────┐  │ │ │     │ │
    │  COMPANY_PROJECTS    │  │ │ │     │ │
    ├──────────────────────┤  │ │ │     │ │
    │ id (PK)              │  │ │ │     │ │
    │ company_id (FK)      │  │ │ │     │ │
    │ title                │  │ │ │     │ │
    │ description          │  │ │ │     │ │
    │ image_url            │  │ │ │     │ │
    └──────────────────────┘  │ │ │     │ │
                              │ │ │     │ │
    ┌──────────────────────┐  │ │ │     │ │
    │ COMPANY_ANALYTICS    │  │ │ │     │ │
    ├──────────────────────┤  │ │ │     │ │
    │ id (PK)              │  │ │ │     │ │
    │ company_id (FK)      │  │ │ │     │ │
    │ visit_date           │  │ │ │     │ │
    │ visit_count          │  │ │ │     │ │
    │ unique(company_id,   │  │ │ │     │ │
    │   visit_date)        │  │ │ │     │ │
    └──────────────────────┘  │ │ │     │ │
                              │ │ │     │ │
                    ┌─────────┴─┘ │     │ │
                    │             │     │ │
                    │         ┌───▼──────┘ │
                    │         │            │
                    │     ┌──▼──────────────────────┐
                    │     │  ARTICLE_IMAGE          │
                    │     ├─────────────────────────┤
                    │     │ id (PK)                 │
                    │     │ uploaded_by (FK→Users)  │
                    │     │ article_id (FK)         │
                    │     │ image_path              │
                    │     │ timestamps              │
                    │     └─────────────────────────┘
                    │
                ┌───▼─────────────────────────────────────────────┐
                │              PIVOT TABLES                        │
                ├───────────────────────────────────────────────────┤
                │                                                   │
                │  ARTICLE_CATEGORIES (Article ↔ Category)        │
                │  ARTICLE_COMPANIES (Article ↔ Company)          │
                │  ARTICLE_EVENTS (Article ↔ Event)               │
                │  ARTICLE_TAGS (Article ↔ Tag)                   │
                │                                                   │
                └───────────────────────────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────────────────────┐
│                         LOCATION HIERARCHY                                          │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│    COUNTRIES       │
├────────────────────┤
│ id (PK)            │
│ sortname           │
│ name               │
│ phonecode          │
│ timestamps         │
└────────────────────┘
         │
         │ 1:N
         │
    ┌────▼──────────┐
    │    STATES     │
    ├───────────────┤
    │ id (PK)       │
    │ name          │
    │ slug          │
    │ state_code    │
    │ country_id(FK)│
    │ timestamps    │
    └───────────────┘
         │
         │ 1:N
         │
    ┌────▼────────┐
    │   CITIES    │
    ├─────────────┤
    │ id (PK)     │
    │ name        │
    │ city_code   │
    │ state_id(FK)│
    │ timestamps  │
    └─────────────┘


┌─────────────────────────────────────────────────────────────────────────────────────┐
│                            EVENTS STRUCTURE                                         │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│      EVENTS        │
├────────────────────┤
│ id (PK)            │
│ organizer_company  │
│   _id (FK)         │
│ title              │
│ slug (UNQ)         │
│ description        │
│ event_type         │
│ start_datetime     │
│ end_datetime       │
│ location_name      │
│ city_id            │
│ is_virtual         │
│ price              │
│ ticket_url         │
│ is_featured        │
│ banner_image_url   │
│ social_links       │
│ community_links    │
│ timestamps         │
└────────────────────┘
         │
         │ 1:N (Foreign Key)
         │
    ┌────▼──────────────┐
    │ EVENT_GALLERIES   │
    ├───────────────────┤
    │ id (PK)           │
    │ event_id (FK)     │
    │ image_url         │
    └───────────────────┘

    ┌──────────────────┐
    │ EVENT_SPEAKERS   │
    ├──────────────────┤
    │ id (PK)          │
    │ event_id (FK)    │
    │ name             │
    │ position         │
    │ image_url        │
    └──────────────────┘
         │
         │ M:N
         │
    ┌────▼──────────────────┐
    │  EVENT_ORGANIZERS     │
    ├───────────────────────┤
    │ id (PK)               │
    │ event_id (FK)         │
    │ company_id (FK)       │
    │ timestamps            │
    └───────────────────────┘

         │
         │ M:N
         │
    ┌────▼──────────────────┐
    │  EVENT_PARTNERS       │
    ├───────────────────────┤
    │ id (PK)               │
    │ event_id (FK)         │
    │ company_id (FK)       │
    │ type (sponsor,        │
    │       partner,        │
    │       media_partner)  │
    │ timestamps            │
    └───────────────────────┘

         │
         │ M:N
         │
    ┌────▼──────────────────┐
    │   EVENT_TAGS          │
    ├───────────────────────┤
    │ event_id (FK)         │
    │ tag_id (FK)           │
    │ PK(event_id, tag_id)  │
    └───────────────────────┘

         │
         │ M:N
         │
    ┌────▼──────────────────┐
    │  CATEGORY_EVENT       │
    ├───────────────────────┤
    │ category_id (PK)      │
    │ event_id (PK)         │
    │ timestamps            │
    └───────────────────────┘


┌─────────────────────────────────────────────────────────────────────────────────────┐
│                            JOBS STRUCTURE                                           │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│      JOBS          │
├────────────────────┤
│ id (PK)            │
│ company_id (FK)    │
│ company_name       │
│ title              │
│ slug (UNQ)         │
│ description        │
│ summary            │
│ job_type           │
│ location           │
│ city_id (FK)       │
│ is_remote          │
│ salary_range       │
│ experience_level   │
│ education_level    │
│ apply_link         │
│ skills             │
│ is_featured        │
│ is_urgent          │
│ status             │
│ expires_at         │
│ timestamps         │
│ responsibilities   │
│ requirements       │
│ application_type   │
└────────────────────┘
         │
         │ M:N
         │
    ┌────▼─────────────────┐
    │   JOB_TOOL (Stack)   │
    ├──────────────────────┤
    │ id (PK)              │
    │ job_id (FK)          │
    │ stack_id (FK)        │
    │ timestamps           │
    └──────────────────────┘


┌─────────────────────────────────────────────────────────────────────────────────────┐
│                      TAXONOMIES & STACKS                                            │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│    CATEGORIES      │
├────────────────────┤
│ id (PK)            │
│ name               │
│ slug (UNQ)         │
│ parent_id          │
│ type               │
│ icon_class         │
└────────────────────┘
         │ M:N
         ├────────────────────────┬──────────────────────┐
         │                        │                      │
    ┌────▼───────────────┐   ┌───▼──────────────┐   ┌──▼─────────────────┐
    │ COMPANY_CATEGORIES │   │ ARTICLE_CATEGORIES
    ├────────────────────┤   └───────────────────┘
    │ company_id (FK)    │
    │ category_id (FK)   │
    │ PK(company_id,     │
    │    category_id)    │
    └────────────────────┘

┌────────────────────┐
│       TAGS         │
├────────────────────┤
│ id (PK)            │
│ name (UNQ)         │
└────────────────────┘
         │ M:N
         ├────────────────────────┬──────────────────────┐
         │                        │                      │
    ┌────▼──────────────┐   ┌────▼──────────────┐   ┌──▼──────────────┐
    │  ARTICLE_TAGS     │   │  EVENT_TAGS       │   │ (others)        │
    └───────────────────┘   └───────────────────┘   └─────────────────┘

┌────────────────────┐
│     STACKS         │
├────────────────────┤
│ id (PK)            │
│ name               │
│ icon_class         │
│ category           │
│ timestamps         │
└────────────────────┘
         │ M:N
         ├────────────────────────┬──────────────────────┐
         │                        │                      │
    ┌────▼──────────────┐   ┌────▼──────────────┐   ┌──▼───────────────┐
    │ COMPANY_STACKS    │   │   JOB_TOOL        │   │COMPANY_STACK      │
    ├───────────────────┤   └───────────────────┘   ├──────────────────┤
    │ id (PK)           │                           │ id (PK)          │
    │ company_id (FK)   │                           │ company_id (FK)  │
    │ stack_id (FK)     │                           │ stack_id (FK)    │
    │ timestamps        │                           │ timestamps       │
    └───────────────────┘                           └──────────────────┘


┌─────────────────────────────────────────────────────────────────────────────────────┐
│                         REVIEWS & SAVED ITEMS                                       │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│     REVIEWS        │
├────────────────────┤
│ id (PK)            │
│ item_id (Polymorp.)
│ item_type          │
│ user_id (FK)       │
│ rating             │
│ comment            │
│ is_approved        │
│ timestamps         │
└────────────────────┘
   (Polymorphic)
     ├─ item_type = "App\Models\Company"
     ├─ item_type = "App\Models\Event"
     └─ item_type = "App\Models\Article"


┌────────────────────┐
│  SAVED_ITEMS       │
├────────────────────┤
│ id (PK)            │
│ user_id (FK)       │
│ item_id (Polymorp.)
│ item_type          │
│ timestamps         │
│ UNIQUE(user_id,    │
│   item_id,         │
│   item_type)       │
└────────────────────┘
   (Polymorphic)
     ├─ item_type = "App\Models\Company"
     ├─ item_type = "App\Models\Event"
     ├─ item_type = "App\Models\Article"
     └─ item_type = "App\Models\Job"


┌─────────────────────────────────────────────────────────────────────────────────────┐
│                            CACHE TABLE                                              │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌────────────────────┐
│      CACHE         │
├────────────────────┤
│ key (PK) - String  │
│ value              │
│ expiration - Int   │
└────────────────────┘
```

---
