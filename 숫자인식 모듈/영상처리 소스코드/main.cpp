#include <opencv2/opencv.hpp>  //  Include 'OpenCV' library.

#include <iostream>
#include <cstdlib>
#include <fstream>
#include <stdio.h>

#include <unistd.h>
#include <ctime>
#include <raspicam/raspicam.h>

#include <tesseract/baseapi.h> 
#include "mysql/client_plugin.h" 
#include <leptonica/allheaders.h> 
#include <mysql.h> 
#include <string>


using namespace cv;
using namespace std;

class FFError 
{ 
public: 
 std::string    Label; 
 
 FFError() { Label = (char *)"Generic Error"; } 
 FFError(char *message) { Label = message; } 
 ~FFError() { } 
 inline const char*   GetMessage(void) { return Label.c_str(); } 
};


int main( int argc,char **argv )
{
raspicam::RaspiCam Camera; //Cmaera object
 
    //Open camera
    cout<<"Opening Camera..."<<endl;
    if ( !Camera.open()) {cerr<<"Error opening camera"<<endl;return -1;}
 
    //wait a while until camera stabilizes
    cout<<"Sleeping for 3 secs"<<endl;
    sleep(3);
 
 
    //capture
    Camera.grab();
 
    //allocate memory
    unsigned char *data=new unsigned char[  Camera.getImageTypeSize ( raspicam::RASPICAM_FORMAT_RGB )];
 
    //extract the image in rgb format
    Camera.retrieve ( data,raspicam::RASPICAM_FORMAT_RGB );//get camera image
 
    //save
    std::ofstream outFile ( "33.JPG",std::ios::binary );
    outFile<<"P6\n"<<Camera.getWidth() <<" "<<Camera.getHeight() <<" 255\n";
    outFile.write ( ( char* ) data, Camera.getImageTypeSize ( raspicam::RASPICAM_FORMAT_RGB ) );
    cout<<"Image saved at raspicam_image.ppm"<<endl;
 
    //free resrources
    delete data;    





    Mat image, image2, image3, drawing;  //  Make images.
    Rect rect, temp_rect;  //  Make temporary rectangles.
    vector<vector<Point> > contours;  //  Vectors for 'findContours' function.
    vector<Vec4i> hierarchy;

    double ratio, delta_x, delta_y, gradient;  //  Variables for 'Snake' algorithm.
    int select, plate_width, count, friend_count = 0, refinery_count = 0;



    image = imread("/home/pi/capstone/33.JPG");  //  Load an image file.
    cv::flip(image,image,-1);
    imwrite("/home/pi/capstone/33.JPG", image); 
	cv::flip(image,imager,-1);

    //imshow("Original", image);
    //waitKey(0);



    image.copyTo(image2);  //  Copy to temporary images.
    image.copyTo(image3);  //  'image2' - to preprocessing, 'image3' - to 'Snake' algorithm.

    cvtColor(image2, image2, CV_BGR2GRAY);  //  Convert to gray image.
    //imshow("Original->Gray", image2);
    //waitKey(0);

    Canny(image2, image2, 80, 280, 3);  //  Getting edges by Canny algorithm.
    //imshow("Original->Gray->Canny", image2);
    //waitKey(0);


    //  Finding contours.
    findContours(image2, contours, hierarchy, CV_RETR_TREE, CV_CHAIN_APPROX_SIMPLE, Point());
    vector<vector<Point> > contours_poly(contours.size());
    vector<Rect> boundRect(contours.size());
    vector<Rect> boundRect2(contours.size());

    //  Bind rectangle to every rectangle.
    for(int i = 0; i< contours.size(); i++){
        approxPolyDP(Mat(contours[i]), contours_poly[i], 1, true);
        boundRect[i] = boundingRect(Mat(contours_poly[i]));
    }

    drawing = Mat::zeros(image2.size(), CV_8UC3);

    for(int i = 0; i< contours.size(); i++){

        ratio = (double) boundRect[i].height / boundRect[i].width;

        //  Filtering rectangles height/width ratio, and size.
        if((ratio <= 3) && (ratio >= 0.5) && (boundRect[i].area() <= 700)&& (boundRect[i].area() >= 100)){

            drawContours(drawing, contours, i, Scalar(0,255,255), 1, 8, hierarchy, 0, Point());
            rectangle(drawing, boundRect[i].tl(), boundRect[i].br(), Scalar(255,0,0), 1, 8, 0);

            //  Include only suitable rectangles.
            boundRect2[refinery_count] = boundRect[i];
            refinery_count += 1;
        }
    }

    boundRect2.resize(refinery_count);  //  Resize refinery rectangle array.

    //imshow("Original->Gray->Canny->Contours&Rectangles", drawing);
    //waitKey(0);



    //  Bubble Sort accordance with X-coordinate.
    for(int i=0; i<boundRect2.size(); i++){
        for(int j=0; j<(boundRect2.size()-i); j++){
            if(boundRect2[j].tl().x > boundRect2[j+1].tl().x){
                temp_rect = boundRect2[j];
                boundRect2[j] = boundRect2[j+1];
                boundRect2[j+1] = temp_rect;
            }
        }
    }


    for(int i = 0; i< boundRect2.size(); i++){

        rectangle(image3, boundRect2[i].tl(), boundRect2[i].br(), Scalar(0,255,0), 1, 8, 0);

        count = 0;

        //  Snake moves to right, for eating his freind.
        for(int j=i+1; j<boundRect2.size(); j++){

            delta_x = abs(boundRect2[j].tl().x - boundRect2[i].tl().x);

            if(delta_x > 150)  //  Can't eat snake friend too far ^-^.
                break;

            delta_y = abs(boundRect2[j].tl().y - boundRect2[i].tl().y);


            //  If delta length is 0, it causes a divide-by-zero error.
            if(delta_x == 0){
                delta_x = 1;
            }

            if(delta_y == 0){
                delta_y = 1;
            }


            gradient = delta_y / delta_x;  //  Get gradient.
            //cout << gradient << endl;

            if(gradient < 0.25){  //  Can eat friends only on straight line.
                count += 1;
            }
        }

            //  Find the most full snake.
            if(count > friend_count){
                select = i;  //  Save most full snake number.
                friend_count = count;  //  Renewal number of friends hunting.
                rectangle(image3, boundRect2[select].tl(), boundRect2[select].br(), Scalar(255,0,0), 1, 8, 0);
                plate_width = delta_x;  //  Save the last friend ate position.
            }                           //  It's similar to license plate width, Right?
    }

    //  Drawing most full snake friend on the image.
    rectangle(image3, boundRect2[select].tl(), boundRect2[select].br(), Scalar(0,0,255), 2, 8, 0);
    line(image3, boundRect2[select].tl(), Point(boundRect2[select].tl().x+plate_width, boundRect2[select].tl().y), Scalar(0,0,255), 1, 8, 0);

    //imshow("Rectangles on original", image3);
    //waitKey(0);



    //  Shows license plate, and save image file.
    //imshow("Region of interest", image(Rect(boundRect2[select].tl().x-100, boundRect2[select].tl().y-200, plate_width+300, plate_width*2)));
    //waitKey(0);

    imwrite("/home/pi/capstone/1-1.JPG",
            image(Rect(boundRect2[select].tl().x-100, boundRect2[select].tl().y-200, plate_width+300, plate_width*2)));
   
  

    char *outText; 
 char ch[100]; //조건문 거른값 
 char num[100];// 임시 메모리 버퍼 
 int j = 0, c_count = 1; 
  
 tesseract::TessBaseAPI *api = new tesseract::TessBaseAPI(); 
 
 if (api->Init(NULL, "eng")) { 
  fprintf(stderr, "Could not initialize tesseract.\n"); 
  exit(1); 
 } 
 
 Pix *i_image = pixRead("/home/pi/capstone/1-1.JPG"); 
 api->SetImage(i_image); 
 outText = api->GetUTF8Text(); 
 
 for (int i = 0; i<strlen(outText); i++) { 
  if (outText[i] >= '0' && outText[i] <= '9') { 
   cout << outText[i] << endl; 
   while (j<c_count) { 
    ch[j] = outText[i]; 
    j++; 
   } 
   c_count++; 
  } 
 } 
 //ch[strlen(ch)-3] = '\0'; 
 api->End(); 
 pixDestroy(&i_image); 

char result[4];
for(int i = 0; i<4; i++)
{
    result[i] = ch[i];
}
  
 // -------------------------------------------------------------------- 
 // Connect to the database 
 
 MYSQL      *MySQLConRet; 
 MYSQL      *MySQLConnection = NULL; 
 
 string hostName = "localhost"; 
 string userId = "root"; 
 string password = "1234"; 
 
 MySQLConnection = mysql_init(NULL); 
 
 try 
 { 
  MySQLConRet = mysql_real_connect(MySQLConnection, 
   hostName.c_str(), 
   userId.c_str(), 
   password.c_str(), 
   NULL,  // No database specified 
   0, 
   NULL, 
   0); 
 
  if (MySQLConRet == NULL) 
   throw FFError((char*)mysql_error(MySQLConnection)); 
 
  
 
 } 
 catch (FFError e) 
 { 
  printf("%s\n", e.Label.c_str()); 
  return 1; 
 } 
 
  
 
 
 if (mysql_query(MySQLConnection, "USE cap")) 
 { 
  printf("Error %u: %s\n", mysql_errno(MySQLConnection), mysql_error(MySQLConnection)); 
  return(1); 
 } 
 sprintf(num, "INSERT INTO number VALUES ( (SELECT NOW()),'%s' )", result); 
 
 //char test[] = "INSERT INTO number VALUES ( (SELECT NOW()),'%c' )",ch; 
 
 if (mysql_query(MySQLConnection, num)) 
 { 
  printf("Error %u: %s\n", mysql_errno(MySQLConnection), mysql_error(MySQLConnection)); 
  return(1); 
 } 
 
 // -------------------------------------------------------------------- 
 // Close datbase connection 
 
 mysql_close(MySQLConnection); 
 
 return 0; 
    


    
}

