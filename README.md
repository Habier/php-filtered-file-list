# Listing files for download purposes
List the files in the current dir, filtering them by file extension. Like: .zip, .rar, etc.

# Instructions
Modify the variables at the start of the **index.php** to customize.  


**$sharedFolder = '/path/to/shared/folder';** //Same folder by default  
**$allowedFileExtensions = ['rar', 'zip'];**  //Empty array to allow all
**$allowedIps = ['0.0.0.0', '192.168.1.35'];** //Empty array to allow everyone  