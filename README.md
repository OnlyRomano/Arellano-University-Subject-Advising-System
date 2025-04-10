# Subject Advising System (AUSAS)

## 📘 Overview

This is a **Subject Advising System** developed for **Arellano University** as a final project for the course **ITC127 - Advanced Database Systems**. It is designed to streamline account, student, subject, grades, and advising management for different user roles: Administrator, Registrar/Staff, and Student.

## 🔐 Login Form

![image](https://github.com/user-attachments/assets/86ad41a7-1dc7-4146-9dd6-71eca366f28b)

Figure 1: Login Form
Figure 1 shows the login form for the Arellano University Subject Advancing System (AUSAS). It contains two (2) textboxes, a button, and a checkbox. The first textbox is designated for the user to input their username, while the second textbox is intended for the user’s password. The button is used to validate the given credentials for authentication. More so, there is a checkbox that enables the “Remember me” function, allowing the user to save their credentials for future usage.

---

## 👥 User Roles and Dashboards

### 🛠 Administrator

![image](https://github.com/user-attachments/assets/8e104b55-a39e-405c-b959-b7bd2b69b469)

Figure 1.1: Administrator
Figure 1.1 depicts the homepage or index of the Arellano University Subject Advancing System (AUSAS). Upon logging in as an ADMINISTRATOR, it grants overall access across the database and the system. Consequently, the homepage displays six (6) buttons, enabling the user to access Accounts Management, where all the accounts saved in the database are located; Students Management, where all the registered students are recorded; Subjects Management, where the subject description and pre-requisites are displayed; Grades Management, where the student’s information and their list of grades are found; Advising Management, where the student's information and list of subjects are located; and a button that displays the username, which the user can access to change their password.



---

### 🧾 Registrar/Staff

![image](https://github.com/user-attachments/assets/5b700189-77ef-4623-a781-72be62bc0c49)

Figure 1.2: Registrar/Staff
Figure 1.2 depicts the homepage or index of the Arellano University Subject Advancing System (AUSAS). Upon logging in as a REGISTRAR or STAFF, it grants limited access across the database and the system. Consequently, the homepage displays four (4) buttons, enabling the user to access Students Management, where all the registered students are recorded; Subjects Management, where the subject description and pre-requisites are displayed; Grades Management, where the student’s information and their list of grades are found; and a button that displays the username, which the user can access to change their password.


---

### 🎓 Student

![image](https://github.com/user-attachments/assets/f21ac837-15b6-4fe0-9c32-b3925dd7f77e)

Figure 1.3: Student
Figure 1.3 shows the homepage or index of the Arellano University Subject Advancing System (AUSAS). Upon logging in as a STUDENT, access is restricted to certain features that only allow the user to view their grades and subjects. Ergo, the homepage will only display three (3) buttons, enabling the user to access the View Grades option where the user can locate their grades, Advising Subjects where the user can locate their list of subjects and its description, and a button that displays the username, which the user can access to change their password.


---

## 🧑‍💼 Accounts Management
![image](https://github.com/user-attachments/assets/74b4bf05-15c1-486a-a226-4133c8c85158)
Figure 2: Accounts Management

Figure 2 presents the Accounts Management page of the Arellano University Subject Advancing System (AUSAS), where all the accounts in the database are recorded and managed. This page features a header with a Home button that redirects the user to the homepage and displays the username and the user type for reference. A search bar and a button are also located, which provide ease of navigation. Additionally, there is a hyperlink labeled Create New Account, which the user can access to create a brand-new account. Above all, a table is found in the body that displays the details of all the registered accounts, such as their username, user type, user status, who created the account, the date the account was created, and all the actions that can be executed on the account, labeled as update and delete. The update option will allow the user to modify their given information, while delete permanently removes their account



### ➕ Create New Account
![image](https://github.com/user-attachments/assets/cd444ea5-f24b-4ff5-92b4-b31261285c0c)

Figure 2.1: Create New Account

Figure 2.1 displays the Create New Account form for Accounts Management. It contains two (2) textboxes, a checkbox, a combo box, and two (2) buttons. The first textbox is intended to accept the user’s chosen username, while the second textbox is for their chosen password. The checkbox allows the user to reveal their password when ticked, providing an error-proof approach in this segment. Furthermore, there is a combo box where the user can select their account type, which could be: ADMINISTRATOR, REGISTRAR/STAFF, or STUDENT. The administrator has overall access to the database and system; the registrar and staff have limited access; and the student may only view certain information on their end. The button is used for submitting the account, which is then recorded in the database and reflected in the accounts management page. The other button is to cancel the action, which redirects back to the Accounts Management Page.


### ✏️ Update Account
![image](https://github.com/user-attachments/assets/bdcefd42-c8fe-4bf5-ab41-029a170932e3)

Figure 2.2: Update Account

Figure 2.2 presents the Update Account action form for Accounts Management. It displays all current data and accepts further modifications. It has a textbox, a checkbox, two (2) radio buttons, and two (2) buttons. The textbox is allotted for the password, where the user can change it. The checkbox allows the user to reveal its password when ticked, allowing the user to visibly make changes to this segment. The radio buttons allow the user to set the status of the account. The update button is to save all changes made, and the cancel button cancels the action and redirects back to the Accounts Management page.



### ❌ Delete Account
![image](https://github.com/user-attachments/assets/7347cdbe-394a-4031-aeca-3787749622e8)

Figure 2.3: Delete Account

Figure 2.3 displays a delete modal that contains text asking for permission to delete the account and two (2) buttons. The Yes button permits the account deletion, which permanently deletes the account from the Accounts Management page. While the No button cancels this action, closing the modal.


---

## 🧑‍🎓 Students Management
![image](https://github.com/user-attachments/assets/1ea6e98b-8282-4f71-ab64-de13e9369eb9)

Figure 3: Students Management

Figure 3 shows the Students Management page of the Arellano University Subject Advancing System (AUSAS), where all the students’ accounts are recorded and managed. This page features a header with a Home button that redirects the user to the homepage and displays the username and the user type for reference. A search bar and a button are also located, which provide ease of navigation. Additionally, there is a hyperlink labeled Create New Students, which the user can access to create a brand-new account. Above all, a table is found in the body that displays the details of all the registered students, such as their student number, last name, first name, middle name, course, year level, who created the account, the date the account was created, and all the actions that can be executed on the account, labeled as update and delete. The update option will allow the user to modify their given information, while delete permanently removes their account.



### ➕ Create Student Account
![image](https://github.com/user-attachments/assets/8d0dae3c-3bb5-4237-82ea-3ee29e1d9629)

Figure 3.1: Create Students Account

Figure 3.1 shows the Create Students Account form for Students Management. It contains five (5) textboxes, a checkbox, two (2) combo boxes, and two (2) buttons. The first four textboxes are intended to accept the user’s student number, last name, first name, and middle name, while the fifth textbox is for their chosen password. The first combo box allows the user to select their chosen course, while the second one lets the user select their year level. The checkbox allows the user to reveal their password when ticked, providing an error-proof approach in this segment. Furthermore, the Submit button is used for submitting the form, which is then recorded in the database and reflected on the student’s management page. The other button is to cancel the action, which redirects back to the Students Management Page.





### ✏️ Update Student Account
![image](https://github.com/user-attachments/assets/ffbbe5a1-77ae-4091-8850-e94f9bdd7e70)

Figure 3.2: Update Student’s Account

Figure 3.2 presents the Update Student’s Account form for Students Management. It displays all current data and accepts further modifications. It has four (4) textboxes, two (2) combo boxes, and two (2) buttons. The textboxes are allotted for further changes to the user’s student number and name. The checkboxes allow the user to reselect their course and year level. The update button allows the user to save all changes made, and the cancel button cancels the action and redirects back to the Students Management page.



### ❌ Delete Student Account
![image](https://github.com/user-attachments/assets/47843ea7-9d36-481d-bbaa-ce87c5507ce2)

Figure 3.3: Delete Student’s Account

Figure 3.3 displays a delete modal that contains text asking for permission to delete the account and two (2) buttons. The Yes button permits the account deletion, which permanently deletes the account from the Students Management page. While the No button cancels this action, closing the modal.


---

## 📚 Subject Management
![image](https://github.com/user-attachments/assets/f82a6ffe-9d22-479d-a89f-8cdba68e9950)

Figure 4: Subject Management

Figure 4 displays the Subject Management page of the Arellano University Subject Advancing System (AUSAS), where all the subjects are recorded and managed. This page features a header with a Home button that redirects the user to the homepage and displays the username and the user type for reference. A search bar and a button are also located, which provide ease of navigation. Additionally, there is a hyperlink labeled Create New Subjects, which the user can access to add another subject. Above all, a table is found in the body that displays the information regarding the subject, such as its subject code, subject description, number of units, prerequisites, who added the subject, the date the subject was added, and all the actions that can be executed on the subject, labeled as update and delete. The update option will allow the user to modify the current information stored, while delete permanently removes the subject.


### ➕ Create Subject
![image](https://github.com/user-attachments/assets/b9818b46-8d8c-4875-a28a-e9894c623c84)

Figure 4.1: Create New Subjects

Figure 4.1 shows the Create Subjects form for Subject Management. It contains two (2) textboxes, five (5) combo boxes, and two (2) buttons. The two textboxes are intended for the subject code and subject description. The first combo box allows the user to select the number of units of a subject, while the second combo box allows the user to select its corresponding course. The remaining combo boxes allow the user to choose or assign the subject’s prerequisites. Furthermore, it has a submit button, which is used for submitting the form, which then records it in the database and is reflected on the subject management page. The other button is to cancel the action, which redirects back to the Students Management Page.


### ✏️ Update Subject
![image](https://github.com/user-attachments/assets/998728fe-16f1-4236-8629-759be1ef6b63)

Figure 4.2: Update Subjects

Figure 4.2 presents the Update Subject form for Subject Management. It displays all current data and accepts further modifications. It has two (2) textboxes, five (5) combo boxes, and two (2) buttons. The textboxes are allotted for further changes to the subject code and subjects’ description. The first two (2) checkboxes allow the user to reselect the number of units of a subject and its corresponding course, while the remaining combo boxes are to modify the subjects’ prerequisites. The update button allows the user to save all changes made, and the cancel button cancels the action and redirects back to the Students Management page.


### ❌ Delete Subject
![image](https://github.com/user-attachments/assets/3081d57c-453d-4f16-83ec-41e617cfb790)

Figure 4.3: Delete Subject

Figure 4.3 displays a delete modal that contains text asking for permission to delete the subject and two (2) buttons. The Yes button permits the subject deletion, which permanently deletes the account from the Subject Management page. While the No button cancels this action, closing the modal.

---

## 📝 Grades Management (Admin)

![image](https://github.com/user-attachments/assets/f42f4ce6-6846-4186-9b1a-303c15a4d8c8)

Figure 5: Grades Management (Admin)

Figure 5 displays the Grade Management page of the Arellano University Subject Advancing System (AUSAS), where the grades of students are recorded and managed. This page features a header with a Home button that redirects the user to the homepage and displays the username and the user type for reference. A search bar and a button are also located, which allows you to search for the student and display their details and list of grades.

---

## 📊 View Student Grades

![image](https://github.com/user-attachments/assets/c698f781-98a8-4fcb-ae32-9082893563ba)

Figure 5.1: Student Information & List of Grades

Figure 5.1 shows the outcome of searching for a student. Figure 5.1 displays the Student number, Name, Course, and Year level of the student, and also allows the user to add a non-existing grade for the student. Chiefly, this segment displays the subjects that the student is currently taking, its description, number of units, given grade, and all the actions that can be executed on the given grade, labeled as update and delete. The update option will allow the user to modify the current information stored, while delete permanently removes the grade


---

## 🧾 Advising Management (Admin)

![image](https://github.com/user-attachments/assets/515764f0-04d2-4ad6-93c3-8eb24569cd12)

![image](https://github.com/user-attachments/assets/6259d198-ec09-43cd-8396-4c03f26ed14c)

Figure 6: Advising Management (Admin)

Figure 6 displays the Advising Management page of the Arellano University Subject Advancing System (AUSAS), where the required subjects of students are recorded and managed. This page features a header with a Home button that redirects the user to the homepage and displays the username and the user type for reference. A search bar and a button are also located, which allows you to search for the student and display their details and advised subjects.


---

## 📋 Advising Subjects (Student)

![image](https://github.com/user-attachments/assets/b5ef8de6-0195-4219-a725-65972a9c6c9a)

Figure 6.1: Student Information & List of Subjects

Figure 6.1 displays the outcome of searching for a student. Figure 6.1 shows the Student number, Name, Course, and Year level of the student. Predominantly, this segment displays the required subjects that the student is supposed to accomplish, their description, the number of units, and their prerequisites.


![image](https://github.com/user-attachments/assets/1c5aa0af-9c30-44e8-baaf-65adbd63565d)

Figure 7: Viewing of Grades

Figure 7 displays the Grades pages which can only be viewed by the students. Figure 7 shows the subject code, its description, the subject’s number of units, and the grade given to the student.


---

## 🔑 Change Password

![image](https://github.com/user-attachments/assets/b672daf1-3792-4dca-8d19-9534897f05cf)

Figure 7.1: Advising Subjects

Figure 7.1 shows the Advising Subject page which can only be viewed by the students. Figure 7.1 displays the student’s information, and the list of subjects that the student is supposed to take, along with its description, and number of units

