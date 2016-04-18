Given(/^user has an account$/) do
	
	visit("https://localhost/login/")
	find('#login-username').set('test@gmail.com')
	find('#login-password').set('test')
	page.execute_script("$('#login-button').click()")
	list = Array.new
	list = find('#account-list').all('li')
	@size = list.size
	@size.should be > 0
end

When(/^user clicks on the delete button$/) do
	page.has_css?('#show_side')
	page.has_css?('.account-option option-edit fa fa-cog')
	page.has_css?('.edit-option delete-button')
	#page.click_button('#show_side')
	page.execute_script("$('#show_side').click()")
	page.execute_script("$('.account-option option-edit fa fa-cog').click()")
	page.execute_script("$('.edit-option delete-button').click()")
end 

Then(/^account list shortens$/) do
	expect(find('#account-list')).to have_selector('li', count: @size)
end
