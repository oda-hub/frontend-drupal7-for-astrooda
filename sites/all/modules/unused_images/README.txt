UNUSED IMAGES
================

The unused images module allows you to find images that are no longer used.

A big pro of this module is that it also finds unmanaged files that are unused!
As a bonus, this module can also show you content that refers to no longer
existing images.

Problem
-------
1) Unused images
Your content probably contains lots of images. Uploaded as managed file, e.g.
via an image field, or directly using a wysiwyg editor or a module like IMCE.
Over time content gets outdated and unpublished, or images get replaced by more
actual or better ones, leaving a growing list of images on your public and/or
private folder that are no longer used.

This is not a big problem, but these images may clutter your media library, take
up space of your hosting storage limit, increase backup times or otherwise slow
down file server actions. Wouldn't it be nice to be able to find images that are
no longer used and safely more or remove them?

2) No longer existing images
Your content and your managed file table may contain references to files that do
no longer exists on the file system. This may lead to on page 404's and warnings
like "[warning] Missing file with ID 1059. ImageItem.php:327" during a migration
to D8. Wouldn't it be nice to be able to find these erroneous references before
migrating, thereby preventing the warnings?

Solution
--------
The core file module keeps track of managed file usage and as such allows you to
relatively easily find unused images or documents and delete them. However, if
you also uploaded lots of images directly, or if you have reasons to distrust
the usage counters of managed files, you can use this module to find and
(re)move them.

Warnings
--------
1) Although this module has been tested and found to be working correctly on a
number of our own sites, it may fail in your situation, e.g. due to:
- Contrib modules not used by us that store references to either the managed
  files or the files directly.

So be sure to always:
- Make a backup of your database before you start.
- Make a backup of your public and/or private filesystem before you start.
- Check your site afterwards, especially for 404's on images and documents.

2) This module uses the default cache bin to store results, so clearing the
cache will clear results of actions that have already run. If this happens you
will have to rerun those actions.

How to use this module and what it does?
----------------------------------------
1) Install and enable this module in the usual way.

2) Assign the right to use this module to the appropriate role(s).
WARNING: this module can have security implications and may bypass access rights
when showing links to content referring to non-existing images. So only assign
to fully trusted roles.

2) Configure the module by:
- Following the link on the module page.
- going to menu-item "Configuration - Media - Unused Images - Settings".
- Browsing to local url admin/config/media/unused-images/settings.

Be sure to read the explanations and implications for the different settings.

3) Run the finders
Go to the status page at "Configuration - Media - Unused Images"
(admin/config/media/unused-images).

On the status page you see a list of "finders" (actions) and their status. The
lists of image and usage finders depend on your selections on the settings page.
The difference finders are standard. Run all finders that are not yet run. The
following types of finders are distinguished:

3.1) Image finders
An image finder creates an overview of existing image files on a given path.
Normally you want to search the public and, if you use it, the private file
system. If an image finder has run the status will show so and indicate how
many files were found. On the run/refresh page you can see a detailed list of
images found.

3.2) Usage finders
A usage finder creates an overview of references to images in a given place.
Places that can be searched for usages:
 1 - Managed files.
 2 - Text fields.
 3 - Custom blocks (the body of it).
 4 - Variables, including translated variables if the variable store contrib
     module is enabled. The logo and favicon are typically referenced via
     (theme) variables.

Places that are not searched:
- Css files, thus the usage of an image as background image will not be found.
  But as you should place your background images in your theme folder anyway (to
  prevent having to use absolute or (those awkward) relative urls (with lots of
  ../) this shouldn't pose any problems.
- Slide show modules that do not store their list of images in one of the above
  mentioned places.

If an usage finder has run the status will show so and indicate how many usages
were found. On the run/refresh page you can see a detailed list of usages found
and which entity(/ies) refer to it.

3.3) Difference finders
Once you have run all image and usage finders, the module can compare the
results and show you:
 1 - Unused images: image files that were found but having no usages.
 2 - Not-existing images: image usages found that refer to an image whose file
     does not exist. This difference finder is a bonus, you could also use an
     external link checker or check your 404 reports.

Differences between an external link checker and this finder:
- Revisions: this finder can check old revisions that refer to no longer
  existing images.
- External images: a link checker can check all kinds of links, not only to
  local images.

As a safe guard you can only run the 1st difference finder when all (selected)
usage finders have run, while the 2nd one can only be run when all (selected)
image finders have run.

At any time you can refresh the results for an action.

4) Move or delete unused images
If unused images were found in step 3.3.1 you can move them to the temporary://
path, or if you are sure and do have a backup, just delete them. When moving
them, the module will create a hierarchical folder structure under
temporary://unused-images/{scheme} that mirrors that of where the stream wrapper
is referring to.

5) Restore no longer existing images, or edit or delete revisions that refer to
them.

Having a list of no longer existing images you may try to recover them from old
backups. Rerunning the image finder(s) and the not existing images difference
finder will show you any progress in this (fewer not existing images).

Instead of restoring the images, you may also decide that the content referring
to it should be edited or deleted. To help you in editing content or content
revisions, or deleting revisions, links to the referring content will be
displayed in the details of the finders.

Alternatively you can decide to just delete all (older) revisions. Use the Node
Revision Delete module (5) to do so.

6) Finished? Disable the module!
If you are finished cleaning up, you better disable this module. Subsequently
uninstalling it will remove the module settings. Deleting the results can (at
all times) be done by clearing the cache.

Limitations
-----------
- This module has only been tested with images but should work with other types
  of documents as well.
- This module considers an entry in the managed files table as a usage and will
  not check if the managed file is used anywhere in a file or image field. See
  the audit files project below that does just that. So consider installing and
  running that module as well.
- This module does not have the performance issues that my Duplicate Images
  module (3) has, see e.g issue [#2827231) (7). Each image finder runs in time
  linear to the amount of files on the targeted file system and each usage
  finder or runs linear to the amount of records in a table. Nevertheless, the
  processing time per record may be relatively high, so on sites with huge
  amounts of content (revisions) or images, time outs are possible.
- This module uses the default cache bin for storing the results of each finder.
  On big sites, this may be a quite some amount of information and though it is
  compressed before being stored, you may still hit limits here of e.g. the
  database driver (variable max_allowed_packet).

Support for other (contrib) modules
-----------------------------------
This module does work with:
- File (Drupal core).
- Image (Drupal core).
- Block (Drupal core).
- Media.
- File entity.
- Other modules (media libraries or that kind) as long as they use the managed
  file table to store images.
- Variable store (translation of variables).
- Variable admin (editing variables (in a given language)).
- IMCE and other modules that allow to upload images directly to the public
  folder, bypassing the managed files concept, and let you reference them in
  text fields.
- i18n: A number of the i18n sub modules store their translations in the locale
  tables which can be searched as well so iamge references in translations can
  be fond as well.

Similar or related contrib modules
----------------------------------
- Fancy File Delete (3): Restricted to finding unused managed files only.
- Audit Files (4): Idem. Note that this project can give you an overview of not
  used managed files and discrepancies between the managed files table and the
  file usages table.
- Node Revision Delete (5): To delete (node) revisions.

Author
------
Erwin Derksen - aka fietserwin (1) - of Buro RaDer (2).

Links
-----
(1) https://www.drupal.org/u/fietserwin
(2) https://www.burorader.com/
(3) https://www.drupal.org/project/duplicate_images
(4) https://www.drupal.org/project/fancy_file_delete
(5) https://www.drupal.org/project/auditfiles
(6) https://www.drupal.org/project/node_revision_delete
(7) https://www.drupal.org/project/duplicate_images/issues/2827231
