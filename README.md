# Org: WPezClasses
### Product: Class_WP_ezClasses_CPT_RPT_1

##### WordPress custom post types done The ezWay. 

Base layer of a stack of (probaby three) classes for RPT'ing (register_post_type()) a new WP CPT.

=======================================================================================

#### WPezClasses: Getting Started
- https://github.com/WPezClasses/wp-ezclasses-docs-getting-started

=======================================================================================

#### Overview

- This class (in this repo) serves as the base. It takes care of some simple housekeeping. You lean on this but there's nothing for you to change here. 

- The next layer up serves as a goto / boilerplate that configures - this is the important bit - a particular type of CPT. For example: https://github.com/WPezClasses/class-wp-ezclasses-cpt-rpt-1-public-exclude-from-search-1.  Again, you lean on this but there's nothing for you to change here. Chances are you'd create a new class for a new middle layer if necessary.

- Finally, on top (so to speak) is where you'll define a handful of setting that are unique to your CPT. For that, use this: https://github.com/WPezClasses/class-wp-ezclasses-cpt-rpt-1-boilerplate-1. This *is* where you'll be making any changes. Notice how few TODOs it takes to get a CPT up and running. 