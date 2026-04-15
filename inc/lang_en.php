<?php

$page_role = [
	'docs'		=> 'docs',
	'account'	=> 'account',
	'sign-in'	=> 'sign-in',
	'sign-out'	=> 'sign-out',
	'terms-of-use'	=> 'terms-of-use',
	'legal-notice'	=> 'legal-notice',
	'contact'		=> 'contact',
	'lost-ids'		=> 'lost-identifiers',
];

$page_title_prefix = 'Sign a PDF for free';

$page_title_suffix = [
	'docs'		=> 'Your documents',
	'account'	=> 'Your account',
	'sign-in'	=> 'Sign in',
	'sign-out'	=> 'Sign out',
	'terms-of-use'	=> 'Terms of use',
	'legal-notice'	=> 'Legal notice',
	'contact'		=> 'Contact',
	'lost-ids'		=> 'Lost identifiers',
];

$tr =[
	'SITE_NAME'				=> 'Sign a PDF',
	'EXPLICT_LANG'			=> 'English',
	'META.KEYWORDS'			=> 'sign, pdf, free, document, signature, image, text, creation, pages, freely',
	'META.DESCRIPTION'		=> "Sign a PDF for free. You can freely send your documents and then choose a source for your signatures between an image created graphically, an image stored on your device or an image created from a text. You can choose the pages on which the signature should be added",
	'BACK'					=> 'Back',
	'CONFIRMATION'			=> 'Confirmation',
	'CANCEL'				=> 'Cancel',
	'CONFIRM'				=> 'Confirm',
	'CONTINUE'				=> 'Continue',
	'WELCOME'				=> 'Welcome',
	'SUBMIT'				=> 'Submit',
	'DELETE'				=> 'Delete',
	'DOWNLOAD'				=> 'Download',
	'CLEAR'					=> 'Clear',
	'SIGNED'				=> 'Signed',
	'UNSIGNED'				=> 'Unsigned',
	'DATE_FORMAT'			=> "m/d/Y H:i:s",
	'MENU.SEND_DOCUMENT'	=> 'Send a document',
	'MENU.YOUR_DOCUMENTS'	=> 'Your documents',
	'MENU.YOUR_SIGNATURES'	=> 'Your signatures',
	'MENU.CREATE_ACCOUNT'	=> 'Create an account',
	'MENU.UPDATE_ACCOUNT'	=> 'Modify your account',
	'MENU.SIGN_IN'			=> 'Sign in',
	'MENU.SIGN_OUT'			=> 'Sign out',
	'MENU.YOUR_ACCOUNT'		=> 'Your account',
	'MENU.TERMS_OF_USE'		=> 'Terms of use',
	'MENU.LEGAL_NOTICE'		=> 'Legal notice',
	'MENU.CONTACT'			=> 'Contact',
	'HOME.INTRO'			=> 'Add a document and you can then sign it for free',
	'HOME.ADVICE'			=> '<a class="common" href="%%account_link%%">Create an account</a>, and you will retrieve easily your documents and your signatures',
	'HOME.ADD_PDF'			=> 'Add a PDF',
	'UPLOAD.SENDING_DOC'	=> 'Sending your document',
	'NOT_AN_IMAGE'			=> 'This file is not recognized as an image',
	'UPLOAD.NOT_A_PDF'		=> 'This file is not recognized as a PDF',
	'UPLOAD.FILE_TOO_BIG'	=> 'The size of this file exceeds the allowed size (20MiB)',
	'UPLOAD.MAX_DOCS_NUMB'	=> 'The maximum number of documents (%%max_docs_numb%%) has been reached, please delete some first',
	'UPLOAD.BYTES_RECEIVED'	=> 'Received',
	'UPLOAD.WAITING_MSG'	=> 'Please wait...',
	'UPLOAD.PREPARING_DOC'	=> 'Pages in preparation',
	'UPLOAD.BYTE_UNITS'		=> "['bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']",
	'DOCS.LIST_DOCUMENTS'	=> 'Here is the list of your documents, signed or not',
	'DOCS.SEE_ALL_DOCS'		=> 'See all my documents',
	'DOCS.YOUR_DOCUMENT'	=> 'Your document',
	'DOCS.SIGN_THIS_DOC'	=> 'Sign',
	'DOCS.SIGN.TITLE'		=> 'Sign a document',
	'DOCS.SIGN.STEP1.INTRO'			=> 'Choose the source of your signature',
	'DOCS.SIGN.STEP2.INTRO'			=> 'Here is a preview of your signature',
	'DOCS.SIGN.STEP2.SIGN_IT'		=> 'Create your signature by drawing into the rectangle',
	'DOCS.SIGN.STEP3.INTRO'			=> 'Choose the pages on which the signature should be added',
	'DOCS.DELETE.CONFIRM'			=> 'Are you sure you want to remove this document ?',
	'SIGN.OPTIONS.CREA.INVITE'		=> 'An image created graphically',
	'SIGN.OPTIONS.CREA.LABEL'		=> '',
	'SIGN.OPTIONS.CREA.PLACEHOLDER'	=> '',
	'SIGN.OPTIONS.CREA.INVALID'		=> '',
	'SIGN.OPTIONS.STOR.INVITE'		=> 'An image stored on your device',
	'SIGN.OPTIONS.STOR.LABEL'		=> 'Choose an image',
	'SIGN.OPTIONS.STOR.PLACEHOLDER'	=> '',
	'SIGN.OPTIONS.STOR.INVALID'		=> 'Invalid file',
	'SIGN.OPTIONS.TEXT.INVITE'		=> 'An image created from text',
	'SIGN.OPTIONS.TEXT.LABEL'		=> 'Text',
	'SIGN.OPTIONS.TEXT.PLACEHOLDER'	=> 'Your signature text',
	'SIGN.OPTIONS.TEXT.INVALID'		=> 'Enter a text',
	'SIGN.PAGES.LAST.INVITE'		=> 'The last page',
	'SIGN.PAGES.LAST.LABEL'			=> '',
	'SIGN.PAGES.LAST.PLACEHOLDER'	=> '',
	'SIGN.PAGES.LAST.INVALID'		=> '',
	'SIGN.PAGES.ALL.INVITE'			=> 'All the pages',
	'SIGN.PAGES.ALL.LABEL'			=> '',
	'SIGN.PAGES.ALL.PLACEHOLDER'	=> '',
	'SIGN.PAGES.ALL.INVALID'		=> '',
	'SIGN.PAGES.CUST.INVITE'		=> "A selection of pages",
	'SIGN.PAGES.CUST.LABEL'			=> 'Pages',
	'SIGN.PAGES.CUST.PLACEHOLDER'	=> 'Example: 1 or 6,7 or 1-4',
	'SIGN.PAGES.CUST.INVALID'		=> 'Incorrect page numbers',
	'SIGN.FILE_TOO_BIG'				=> 'The size of this file exceeds the allowed size (1MiB)',
	'ACCOUNT.CREATE_INTRO'			=> 'By creating an account you will retrieve easily your documents and your signatures',
	'ACCOUNT.UPDATE_INTRO'			=> 'Here you can modify your account easily',
	'ACCOUNT.USER_NAME'				=> 'Name (or username)',
	'ACCOUNT.USER_NAME.PLACEHOLDER'	=> 'Your name',
	'ACCOUNT.USER_NAME.HELP'		=> 'From 4 to 24 characters',
	'ACCOUNT.USER_NAME.ERROR'		=> 'Invalid name',
	'ACCOUNT.USER_MAIL'				=> 'Email address',
	'ACCOUNT.USER_MAIL.PLACEHOLDER'	=> 'Your email address',
	'ACCOUNT.USER_MAIL.HELP'		=> 'A confirmation message will be sent to this address',
	'ACCOUNT.USER_MAIL.ERROR'		=> 'Invalid email address',
	'ACCOUNT.USER_PASS'				=> 'Password',
	'ACCOUNT.USER_PASS.PLACEHOLDER'	=> 'Your password',
	'ACCOUNT.USER_PASS.HELP'		=> 'From 4 to 24 characters',
	'ACCOUNT.USER_PASS.ERROR'		=> 'Invalid password',
	'ACCOUNT.CONFIRM'				=> 'Confirmation',
	'ACCOUNT.CONFIRM.PLACEHOLDER'	=> 'Your confirmation',
	'ACCOUNT.CONFIRM.HELP'			=> '',
	'ACCOUNT.CONFIRM.ERROR'			=> 'Invalid Confirmation',
	'ACCOUNT.USER_OPTIN'			=> "I accept to receive informations from this site",
	'ACCOUNT.USER_ACCEPT'			=> 'I accept the <a href="./terms-of-use" target="_blank" class="common">Terms of use</a>',
	'ACCOUNT.USER_ACCEPT.ERROR'		=> 'You must accept the terms of use',
	'ACCOUNT.UNEXPECTED_ERROR'		=> 'Unexpected error',
	'ACCOUNT.EMAIL_ALREADY_EXISTS'	=> 'This address already exists',
	'ACCOUNT.NAME_ALREADY_EXISTS'	=> 'This name already exists',
	'ACCOUNT.VALIDATION_ERROR'		=> 'The parameters are wrong',
	'ACCOUNT.ALREADY_VALIDATED'		=> 'Your account has already been validated',
	'ACCOUNT.SIGN_IN_INTRO'			=> 'Enter your name and password below.',
	'ACCOUNT.LOGIN_ERROR'			=> 'Incorrect identifiers or account not yet validated',
	'ACCOUNT.DELETE.CONFIRM'		=> 'Are you sure you want to delete this account ?',
	'ACCOUNT.CONFIRM_TITLE'			=> 'Confirm your account',
	'ACCOUNT.CONFIRM_WELCOME'		=> 'Welcome <b>%%user_name%%</b> !<br /><br />A message has just been sent to the address <b>%%user_email%%</b> with a confirmation link.',
	'ACCOUNT.VALIDATION_TITLE'		=> 'Account validatation',
	'ACCOUNT.VALIDATION_WELCOME'	=> 'Thank you for creating an account !<br /><br />You are now signed in.',
	'ACCOUNT.UPDATE_WELCOME'		=> 'Thank you <b>%%user_name%%</b> !<br /><br />A message has just been sent to the address <b>%%user_email%%</b> with a link to retrieve your account.',
	'ACCOUNT.LOST_IDENTIFIERS'		=> 'Lost identifiers',
	'ACCOUNT.LOST_IDS_TITLE'		=> 'Lost identifiers',
	'ACCOUNT.LOST_IDS_INTRO'		=> 'Enter your email address and you will receive a message to indicate how to retrieve your account.',
	'ACCOUNT.LOST_IDS_ERROR'		=> 'Unknown email address or account not yet validated',
	'ACCOUNT.LOST_IDS_MAIL_SENT'	=> 'A message has just been sent to the address <b>%%user_email%%</b> with a link to retrieve your account.',
	'ACCOUNT.DELETE_ACCOUNT'		=> 'Delete this account',
	'ACCOUNT.CREATE.MAIL_TITLE'		=> 'Your account creation',
	'ACCOUNT.UPDATE.MAIL_TITLE'		=> 'Modification of your account',
	'ACCOUNT.LOST_IDS.MAIL_TITLE'	=> 'Lost identifiers',
	'CONTACT.INTRO'					=> 'Send a message to us with this form',
	'CONTACT.NAME.LABEL'			=> 'Name',
	'CONTACT.NAME.PLACEHOLDER'		=> 'Your name',
	'CONTACT.MAIL.LABEL'			=> 'Email address',
	'CONTACT.MAIL.PLACEHOLDER'		=> 'Your email address',
	'CONTACT.TEXT.LABEL'			=> 'Message',
	'CONTACT.TEXT.PLACEHOLDER'		=> 'Your message',
	'CONTACT.THANKS_MSG'			=> 'Thanks for your message. We will respond as soon as possible.',
	'LEGAL_NOTICE.TITLE'			=> "Legal notice",
	'LEGAL_NOTICE.TEXT'				=> "
        <strong>Edition of the site site</strong>
        <br />
        <br />sign-a-pdf.com is a site edited by the company MAPAE.
        <br />
        <br />MAPAE is a micro-company whose head office is located at 6 place Frédéric Mistral 83330 Le Beausset FRANCE, and whose SIREN number is 520838665.
        <br />
        <br />
        <strong>Intellectual property</strong>
        <br />
        <br />The entire site is covered by French and international legislation on copyright and intellectual property. All rights of reproduction are reserved, including iconographic and photographic representations. The reproduction, adaptation and / or translation of all or part of this site on any medium whatsoever, is strictly prohibited without the express authorization of the Director of the publication.
        <br />
        <br />
        <strong>Modification of the site</strong>
        <br />
        <br />The editorial team reserves the right to modify or correct the content of this site and this legal notice at any time and without notice.
        <br />
        <br />
        <strong>Hosting</strong>
        <br />
        <br />The site sign-a-pdf.com is hosted by the company OVH.<br />
        <br />
        OVH<br />
        SAS with capital of 10 069 020 €<br />
        RCS Lille Métropole 424 761 419 00045<br />
        APE Code : 2620Z<br />
        VAT Number : FR 22 424 761 419<br />
        Head office : 2 rue Kellermann - F59100 Roubaix - France<br />",
	'TERMS_OF_USE.TITLE' 			=> "Terms of use",
	'TERMS_OF_USE.TEXT'				=> "These Terms of Use govern your access to and use of sign-a-pdf.com (the “Site”), any information, text, graphics, or other materials appearing on the Site (the “Content”), and any services provided through the Site (the “Services”). Your access to and use of the Site, Content, and/or Services are expressly conditioned on your compliance with these Terms of Use. By accessing or using the Site, Content, or Services, you agree to be bound by these Terms of Use.<br />
		<br />
		<b>Modification of Terms of Use</b><br />
		You acknowledge and agree that the Site may revise these Terms of Use from time to time. By continuing to access or use the Site, Content, or Services after the Site makes any such revision, you agree to be bound by the revised Terms of Use.<br />
		<br />
		<!-- <b>Privacy</b><br />
		See the Site’s Privacy Policy for information and notices concerning the Site’s collection and use of your personal information.<br />
		<br /> -->
		<b>Guard Your Password</b><br />
		You are responsible for safeguarding the password that you use to access any secure areas of the Site. You agree not to disclose your password to any third party. You agree to take sole responsibility for any activities or actions under your password, whether or not you have authorized such activities or actions. You will immediately notify the Site of any unauthorized use of your password.<br />
		<br />
		<b>Your Use of the Content</b><br />
		the Site authorizes you to download, view, and print a single copy of any Content, solely for your personal and non-commercial purposes, and subject to the restrictions set forth in these Terms of Use.<br />
		<br />
		<b>The Site Property</b><br />
		All right, title, and interest in and to the Site, Content, and Services are and will remain the exclusive property of the Site and its licensors. The Site, Content, and Services are protected by copyright, trademark, and other laws of both the United States and foreign countries. Except as expressly permitted in these Terms of Use, you may not reproduce, modify or prepare derivative works based upon, distribute, sell, transfer, publicly display, publicly perform, transmit, or otherwise use the Site, Content, or Services. You may not copy or modify the HTML code used to generate web pages on the Site. You may not use the Site, Content, or Services on or in connection with any other website, for any purpose. the Site respects the intellectual property rights of others and expects our users to do the same.<br />
		<br />
		<b>Terms of subscribing and unsubscribing to services</b><br />
		The subscription to the various services of the site is subjected to the following conditions :<br />
		- The validity of any transaction for a subscription to the various services of the site is not the responsibility of the site but that of the provider by which the transaction was made.<br />
		- For any unsubscription request, or in the event of a dispute, only a request to the service provider through which the transaction was made can be made. Any request to the site for this purpose will be ignored.<br />
		<br />
		<b>General Prohibitions</b><br />
		You agree not to do any of the following while using the Site, Content or Services:<br />
		<br />
		Post, publish or transmit any text, graphics, or material that:<br />
		(i) is false or misleading; <br />
		(ii) is defamatory; <br />
		(iii) invades another’s privacy; <br />
		(iv) is obscene, pornographic, or offensive; <br />
		(v) promotes bigotry, racism, hatred or harm against any individual or group; <br />
		(vi) infringes another’s rights, including any intellectual property rights; or <br />
		(vii) violates, or encourages any conduct that would violate, any applicable law or regulation or would give rise to civil liability;<br />
		Access, tamper with, or use non-public areas of the Site, the Site’s computer systems, or the technical delivery systems of the Site’s providers;<br />
		Attempt to probe, scan, or test the vulnerability of any system or network or breach any security or authentication measures;<br />
		Attempt to access or search the Site, Content, or Services with any engine, software, tool, agent, device or mechanism other than the software and/or search agents provided by the Site or other generally available third party web browsers (such as Microsoft Internet Explorer or Netscape Navigator);<br />
		Send unsolicited email, junk mail, “spam”, or chain letters, or promotions or advertisements for products or services;<br />
		Forge any TCP/IP packet header or any part of the header information in any email or newsgroup posting, or in any way use the Site, Content or Services to send altered, deceptive or false source-identifying information;<br />
		Attempt to decipher, decompile, disassemble or reverse engineer any of the software used to provide the Site, Content, or Services;<br />
		Interfere with, or attempt to interfere with, the access of any user, host or network, including, without limitation, sending a virus, overloading, flooding, spamming, or mail-bombing the Site; or<br />
		Impersonate or misrepresent your affiliation with any person or entity.<br />
		<br />
		The Site will have the right to investigate and prosecute violations of any of the above, including intellectual property rights infringement and Site security issues, to the fullest extent of the law. the Site may involve and cooperate with law enforcement authorities in prosecuting users who violate these Terms of Use. You acknowledge that the Site has no obligation to monitor your access to or use of the Site, Content, and Services, but has the right to do so for the purpose of operating the Site, to ensure your compliance with these Terms of Use, or to comply with applicable law or the order or requirement of a court, administrative agency or other governmental body.<br />
		<br />
		<b>Links</b><br />
		The Site may contain links to third-party websites or resources. You acknowledge and agree that the Site is not responsible or liable for:<br />
		(i) the availability or accuracy of such websites or resources; or<br />
		(ii) the content, products, or services on or available from such websites or resources.<br />
		Links to such websites or resources do not imply any endorsement by the Site of such websites or resources or the content, products, or services available from such websites or resources. You acknowledge sole responsibility for and assume all risk arising from your use of any such websites or resources.<br />
		<br />
		<b>Termination</b><br />
		If you violate any of these Terms of Use, your permission to use the Site, Content, and Services will automatically terminate. the Site reserves the right to revoke your access to and use of the Site, Content, and Services at any time, with or without cause. the Site also reserves the right to cease providing or to change the Site, Content, or Services at any time and without notice.<br />
		<br />
		<b>Use of the Site at Your Own Risk</b><br />
		Your access to and use of the Site, Content, and Services is at your own risk. the Site will have no responsibility for any harm to your computer system, loss of data, or other harm that results from your access to or use of the Site, Content, or Services.<br />
		<br />
		<b>The site is available “as-is”</b><br />
		The site, content, and services are provided “as is”, without warranty or condition of any kind, either express or implied. without limiting the foregoing, the site explicitly disclaims any warranties of merchantability, fitness for a particular purpose, quiet enjoyment or non-infringement. the site makes no warranty that the site, content, or services will meet your requirements or be available on an uninterrupted, secure, or error-free basis. the site makes no warranty regarding the quality of any products, services, or information purchased or obtained through the site, content or services, or the accuracy, timeliness, truthfulness, completeness or reliability of any information obtained through the site, content or services. no advice or information, whether oral or written, obtained from the site or through the site, content, or services, will create any warranty not expressly made herein.<br />
		<br />
		<b>Indemnity</b><br />
		You agree to defend, indemnify, and hold harmless the site, its officers, directors, employees and agents, from and against any claims, liabilities, damages, losses, and expenses, including, without limitation, reasonable legal and accounting fees, arising out of or in any way connected with your access to or use of the site, content, or services, or your violation of these terms of use.<br />
		<br />
		<b>Limitation of liability</b><br />
		To the maximum extent permitted by applicable law, neither the site nor any other party involved in creating, producing, or delivering the site, content, or services will be liable for any incidental, special, consequential or punitive damages resulting from your access to or use of, or inability to access or use, the site, content, or services, whether based on warranty, contract, tort (including negligence) or any other legal theory, whether or not the site has been informed of the possibility of such damage, even if a remedy set forth herein is found to have failed of its essential purpose. you specifically acknowledge that the site is not liable for the defamatory, offensive or illegal conduct of other users or third parties and that the risk of injury from the foregoing rests entirely with you. further, the site will have no liability to you or to any third party for any third-party content uploaded onto or downloaded from the site or through the services.<br />
		<br />
		You agree that the aggregate liability of the site to you for any and all claims arising from the use of the site, content or services is limited to the amounts you have paid to the site for access to and use of the site, content, or services. the limitations of damages set forth above are fundamental elements of the basis of the bargain between the site and you.<br />
		<br />
		<b>Construction</b><br />
		In the event that any provision of these Terms of Use is held to be invalid or unenforceable, the remaining provisions of these Terms of Use will remain in full force and effect. The failure of the Site to enforce any right or provision of these Terms of Use will not be deemed a waiver of such right or provision.<br />
		<br />
		<b>Controlling Law and Jurisdiction</b><br />
		These Terms of Use and all related transactions will be governed by the applicable French laws and must be interpreted in accordance with these laws. Any dispute or dispute relating to the use of the website or to these conditions of use must be submitted to a competent court. Each of the parties hereto waives any objection to jurisdiction and venue in such courts.<br />
		<br />
		<b>Entire Agreement</b><br />
		These Terms of Use are the entire and exclusive agreement between the Site and you regarding the Site, Content, and Services, and these Terms of Use supersede and replace any prior agreements between the Site and you regarding the Site, Content, and Services.<br />
		<br />
		If you have any questions about these Terms of Use, please contact the Site at contact@sign-a-pdf.com. You can also send your request to: MAPAE - 6 place Frederic Mistral - 83330 Le Beausset - FRANCE<br />",
];
