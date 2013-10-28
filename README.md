Web Service Profile (wsprofile)

This is a Drupal module exposing blocks displaying external user profile info for each supported
service (like GitHub or LinkedIn). Blocks called like this: "%service% profile", ex: "LinkedIn profile".

Blocks intended to be used on user profile page sidebar. They would not be visible outside of user profile pages.
Technically speaking they would work on any page having url starting with /user/<id>.

Each block require some permission set (scope) to access user profile data. User should have approved all of these
permissions to get block visible. If there is no enough permissions to fetch all required user profile data block
will not be displayed until user re-authorize service (by 'revoke/allow access to my %service% account' buttons).

There is no CSS stylesheets delivered with this module. So the blocks' look and feel may need to be customized
depending on your current theme.

Blocks available:

- LinkedIn
  Required permsissions: r_basicprofile, r_fullprofile

- GitHub
  Required permissions: no