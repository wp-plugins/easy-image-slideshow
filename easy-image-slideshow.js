/**
 *     Easy image slideshow
 *     Copyright (C) 2012  www.gopipulse.com
 *     http://www.gopipulse.com/work/2012/06/20/easy-image-slideshow-wordpress-plugin/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

function InitiateSlideShow() 
{
	CurrentSlide = -1;
	SimpleSlides[0] = CachedSlideShowImage(SimpleSlides[0]);
	ReadyCache = true;
	SlideShowMoment(1);
}

function CachedSlideShowImage(ISource) 
{ 
	var IObject = new Image();
	IObject.src = ISource;
	return IObject;
}

function SlideShowMoment(Direction) 
{
	if (ReadyCache) 
	{
		NextSlide = CurrentSlide + Direction;
		if(NextSlide == 0)
		{
			//document.images['Previous'].disabled = true;
		}
		if((NextSlide == (SimpleSlides.length-1)))
		{
			//document.images['Next'].disabled = true;
		}
		//document.FormSlideShow.Previous.disabled = (NextSlide == 0);
		//document.FormSlideShow.Next.disabled = (NextSlide == (SimpleSlides.length-1));    
		if ((NextSlide >= 0) && (NextSlide < SimpleSlides.length)) 
		{
			document.images['EasyImage'].src = SimpleSlides[NextSlide].src;
			CurrentSlide = NextSlide++;
			Message = 'Picture ' + (CurrentSlide+1) + ' of ' + SimpleSlides.length;
			self.defaultStatus = Message;
			if (Direction == 1) 
			{
				CacheNextSlide();
			}
		}
		return true;
	}
}

function LocalDownload() 
{
	if (SimpleSlides[NextSlide].complete) 
	{
		ReadyCache = true;
		self.defaultStatus = Message;
	}
	else 
	{
		setTimeout("LocalDownload()", 100);
	}
	return true;
}

function CacheNextSlide() 
{
	if ((NextSlide < SimpleSlides.length) && (typeof SimpleSlides[NextSlide] == 'string'))
	{ 
		ReadyCache = false;
		self.defaultStatus = 'Downloading next picture...';
		SimpleSlides[NextSlide] = CachedSlideShowImage(SimpleSlides[NextSlide]);
		LocalDownload();
	}
   return true;
}